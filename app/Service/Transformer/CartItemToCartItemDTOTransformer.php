<?php


namespace App\Service\Transformer;


use App\Model\CartItem;
use App\Service\DTO\CartItemDTO;

class CartItemToCartItemDTOTransformer
{
    public function transform(CartItem $cartItem) :CartItemDTO
    {
        return new CartItemDTO(
            $cartItem->id,
            $cartItem->cart->id,
            $cartItem->product->id,
            $cartItem->product->title,
            $cartItem->quantity,
            $cartItem->payable_price,
            json_decode($cartItem->detailed_price, 1),
            $cartItem->updated_at,
            $cartItem->created_at
        );
    }
}