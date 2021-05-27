<?php


namespace App\Service;


use App\Service\DTO\CartDTO;

interface CartServiceInterface
{
    public function create() : CartDTO;
    public function addItem(int $cartId, int $productId) : CartDTO;
    public function removeItem(int $cartId, int $productId) : CartDTO;
    public function changeQuantity(int $cartId, int $productId, int $quantity) : CartDTO;
    public function close(int $cartId) : CartDTO;
}
