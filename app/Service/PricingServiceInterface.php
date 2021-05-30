<?php


namespace App\Service;


interface PricingServiceInterface
{
    public function calculate(int $productId, int $quantity, array $pricingRules = null): array;
    public function getProductsPricingRules(array $productIdQuantityMap): array;
}