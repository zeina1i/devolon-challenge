<?php


namespace App\Exceptions;


class CartItemExistsException extends ExistsException
{
    public function __construct(int $cartId, int $productId)
    {
        parent::__construct(sprintf('cart_item with cart_id %s and product_id %s exists', $cartId, $productId));
    }
}