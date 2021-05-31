<?php

namespace App\Service;

use App\Enum\CartEnums;
use App\Exceptions\CartItemExistsException;
use App\Exceptions\CartItemNotFoundException;
use App\Exceptions\ClosedCartException;
use App\Exceptions\EntityNotFoundException;
use App\Model\Cart;
use App\Model\CartItem;
use App\Model\Product;
use App\Service\DTO\CartDTO;
use App\Service\Transformer\CartToCartDTOTransformer;
use Illuminate\Support\Facades\DB;

class CartService implements CartServiceInterface
{
    private $cartToCartDTOTransformer;
    private $cartModel;
    private $cartItemModel;
    private $productModel;
    private $pricingService;
    private $DB;

    public function __construct(
        CartToCartDTOTransformer $cartToCartDTOTransformer,
        Cart $cartModel,
        CartItem $cartItemModel,
        Product $productModel,
        PricingServiceInterface $pricingService,
        DB $DB
    )
    {
        $this->cartToCartDTOTransformer = $cartToCartDTOTransformer;
        $this->cartModel = $cartModel;
        $this->cartItemModel = $cartItemModel;
        $this->productModel = $productModel;
        $this->pricingService = $pricingService;
        $this->DB = $DB;
    }

    public function create(): CartDTO
    {
        $cart = new $this->cartModel;
        $cart->cart_status = CartEnums::CART_STATUS_OPEN;
        $cart->closed_at = null;
        $cart->payable_price = 0;
        $cart->save();

        return $this->cartToCartDTOTransformer->transform($cart);
    }

    public function addItem(int $cartId, int $productId): CartDTO
    {
        $cart = $this->cartModel->find($cartId);
        if ($cart == null) {
            throw new EntityNotFoundException('cart', $cartId);
        }
        if ($cart->cart_status == CartEnums::CART_STATUS_CLOSED) {
            throw new ClosedCartException();
        }

        $product = $this->productModel->find($productId);
        if ($product == null) {
            throw new EntityNotFoundException('product', $productId);
        }

        $cartItem = $this->cartItemModel
            ->where(['cart_id' => $cartId, 'product_id' => $productId])
            ->first();
        if ($cartItem != null) {
            throw new CartItemExistsException($cartId, $productId);
        }


        $this->DB::transaction(function() use ($cartId, $productId, $cart) {
            /* ------------------------------------------------------
             price array includes the total_price in the 0 index,
             and detailed pricing rules which is used in the calculation in the 1 index
            ---------------------------------------------------------*/
            $priceArray = $this->pricingService->calculate($productId, 1);

            $cartItem = new $this->cartItemModel;
            $cartItem->cart_id = $cartId;
            $cartItem->product_id = $productId;
            $cartItem->quantity = 1;
            $cartItem->payable_price = $priceArray[0];
            $cartItem->detailed_price = json_encode($priceArray[1]);
            $cartItem->save();

            $cart->payable_price += $cartItem->payable_price;
            $cart->save();
        });

        return $this->cartToCartDTOTransformer->transform($cart);
    }

    public function removeItem(int $cartId, int $productId): CartDTO
    {
        $cartItem = $this->cartItemModel->where([
            'cart_id' => $cartId,
            'product_id' => $productId,
        ])->first();
        if ($cartItem == null) {
            throw new CartItemNotFoundException($cartId, $productId);
        }

        $product = $this->productModel->find($productId);
        if ($product == null) {
            throw new EntityNotFoundException('product', $productId);
        }

        $cart = $this->cartModel->find($cartId);
        if ($cart == null) {
            throw new EntityNotFoundException('cart', $cartId);
        }
        if ($cart->cart_status == CartEnums::CART_STATUS_CLOSED) {
            throw new ClosedCartException();
        }

        $this->DB::transaction(function() use ($cartItem, $cart) {
            $cart->payable_price -= $cartItem->payable_price;
            $cart->save();
            $cartItem->delete();
        });

        return $this->cartToCartDTOTransformer->transform($cart);
    }

    public function changeQuantity(int $cartId, int $productId, int $quantity): CartDTO
    {
        $cartItem = $this->cartItemModel->where([
            'cart_id' => $cartId,
            'product_id' => $productId,
        ])->first();
        if ($cartItem == null) {
            throw new CartItemNotFoundException($cartId, $productId);
        }

        $cart = $this->cartModel->find($cartId);
        if ($cart == null) {
            throw new EntityNotFoundException('cart', $cartId);
        }
        if ($cart->cart_status == CartEnums::CART_STATUS_CLOSED) {
            throw new ClosedCartException();
        }

        $this->DB::transaction(function() use ($cartItem, $cart, $quantity, $productId) {
            $cart->payable_price -= $cartItem->payable_price;
          /* ------------------------------------------------------
           price array includes the total_price in the 0 index,
           and detailed pricing rules which is used in the calculation in the 1 index
          ---------------------------------------------------------*/
            $priceArray = $this->pricingService->calculate($productId, $quantity);

            $cartItem->quantity = $quantity;
            $cartItem->payable_price = $priceArray[0];
            $cartItem->detailed_price = json_encode($priceArray[1]);
            $cartItem->save();

            $cart->payable_price += $cartItem->payable_price;
            $cart->save();
        });

        return $this->cartToCartDTOTransformer->transform($cart);
    }

    public function close(int $cartId): CartDTO
    {
        $cart = $this->cartModel->find($cartId);
        if ($cart == null) {
            throw new EntityNotFoundException('cart', $cartId);
        }
        $cart->cart_status = CartEnums::CART_STATUS_CLOSED;
        $cart->closed_at = new \DateTime('now');
        $cart->save();

        return $this->cartToCartDTOTransformer->transform($cart);
    }
}
