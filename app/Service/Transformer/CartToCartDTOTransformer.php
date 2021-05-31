<?php


namespace App\Service\Transformer;


use App\Model\Cart;
use App\Service\DTO\CartDTO;

class CartToCartDTOTransformer
{
    private $cartItemToCartItemDTOTransformer;

    public function __construct(
        CartItemToCartItemDTOTransformer $cartItemToCartItemDTOTransformer
    ) {
        $this->cartItemToCartItemDTOTransformer = $cartItemToCartItemDTOTransformer;
    }

    public function transform(Cart $cart):CartDTO
    {
        $cartItemDTOs = [];
        foreach ($cart->cartItems as $cartItem) {
            $cartItemDTOs[] = $this->cartItemToCartItemDTOTransformer->transform($cartItem);
        }


        return new CartDTO(
            $cart->id,
            $cart->cart_status,
            $cartItemDTOs,
            $cart->payable_price,
            $cart->closed_at,
            $cart->updated_at,
            $cart->created_at
        );
    }
}