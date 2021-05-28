<?php

namespace App\Service;

use App\Enum\CartEnums;
use App\Exceptions\EntityExistsException;
use App\Exceptions\EntityNotFoundException;
use App\Model\Cart;
use App\Model\CartItem;
use App\Service\DTO\CartDTO;
use App\Service\Transformer\CartToCartDTOTransformer;
use Illuminate\Support\Facades\DB;

class CartService implements CartServiceInterface
{
    private $cartToCartDTOTransformer;
    private $cartModel;
    private $cartItemModel;
    private $pricingService;
    private $DB;

    public function __construct(
        CartToCartDTOTransformer $cartToCartDTOTransformer,
        Cart $cartModel,
        CartItem $cartItemModel,
        PricingService $pricingService,
        DB $DB
    )
    {
        $this->cartToCartDTOTransformer = $cartToCartDTOTransformer;
        $this->cartModel = $cartModel;
        $this->cartItemModel = $cartItemModel;
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
            throw new EntityNotFoundException('carts', $cartId);
        }

        $cartItem = $this->cartItemModel
            ->where(['cart_id' => $cartId, 'product_id' => $productId])
            ->first();
        if ($cartItem != null) {
            throw new EntityExistsException('carts', $cartId);
        }

        $this->DB::transaction(function() use ($cartId, $productId, $cart) {
            $cartItem = new $this->cartItemModel;
            $cartItem->cart_id = $cartId;
            $cartItem->product_id = $productId;
            $cartItem->quantity = 1;
            $cartItem->payable_price = $this->pricingService->calculate($productId, 1);
            $cartItem->detailed_price = '{}';
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
            throw new EntityNotFoundException('cart_items', $cartId);
        }

        $cart = $this->cartModel->find($cartId);
        if ($cart == null) {
            throw new EntityNotFoundException('carts', $cartId);
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
            throw new EntityNotFoundException('cart_items', $cartId);
        }

        $cart = $this->cartModel->find($cartId);
        if ($cart == null) {
            throw new EntityNotFoundException('carts', $cartId);
        }

        $this->DB::transaction(function() use ($cartItem, $cart, $quantity, $productId) {
            $cart->payable_price -= $cartItem->payable_price;

            $cartItem->quantity = $quantity;
            $cartItem->payable_price = $this->pricingService->calculate($productId, $quantity);
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
            throw new EntityNotFoundException('carts', $cartId);
        }
        $cart->status = CartEnums::CART_STATUS_CLOSED;
        $cart->save();
    }
}
