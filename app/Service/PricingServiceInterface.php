<?php


namespace App\Service;


interface PricingServiceInterface
{
    public function calculate(int $productId, int $quantity, array $pricingRules = null): int;
    public function getProductsPricingRules(array $productIdQuantityMap): array;
}