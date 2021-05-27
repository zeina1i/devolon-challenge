<?php


namespace App\Service;


use App\Enum\CartEnums;
use App\Model\Cart;
use App\Model\CartItem;
use App\Service\DTO\CartDTO;
use App\Service\Transformer\CartToCartDTOTransformer;

class CartService implements CartServiceInterface
{
    private $cartToCartDTOTransformer;
    private $cartModel;
    private $cartItemModel;

    public function __construct(
        CartToCartDTOTransformer $cartToCartDTOTransformer,
        Cart $cartModel,
        CartItem $cartItemModel
    ) {
        $this->cartToCartDTOTransformer = $cartToCartDTOTransformer;
        $this->cartModel = $cartModel;
        $this->cartItemModel = $cartItemModel;
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
        // TODO: Implement addItem() method.
    }

    public function removeItem(int $cartId, int $productId): CartDTO
    {
        // TODO: Implement removeItem() method.
    }

    public function changeQuantity(int $cartId, int $productId, int $quantity): CartDTO
    {
        // TODO: Implement changeQuantity() method.
    }

    public function close(int $cartId): CartDTO
    {
        // TODO: Implement close() method.
    }
}