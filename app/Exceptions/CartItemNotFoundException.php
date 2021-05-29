<?php


namespace App\Exceptions;


class CartItemNotFoundException extends NotFoundException
{
    public function __construct(int $cartId, int $productId)
    {
        parent::__construct(sprintf('cart_item with cart_id %s and product_id %s not found', $cartId, $productId));
    }
}