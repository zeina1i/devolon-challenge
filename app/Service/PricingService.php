<?php

namespace App\Service;

use App\Model\Product;
use App\Model\SpecialPrice;
use App\Service\DTO\PricingRuleDTO;

class PricingService implements PricingServiceInterface
{
    private $productModel;
    private $specialPriceModel;

    public function __construct(Product $productModel, SpecialPrice $specialPriceModel)
    {
        $this->productModel = $productModel;
        $this->specialPriceModel = $specialPriceModel;
    }

    public function calculate(int $productId, int $quantity, array $pricingRules = null): int
    {
        if ($pricingRules == null) {
            $pricingRules = $this->getProductsPricingRules([$productId => $quantity])[$productId];
        }
/*
 * the keys of $pricingRules is equal to the $pricingRule.quantity
 * for example $pricingRules data is like [1 => PricingRule(quantity: 1, product_id: 2, price: 100), 3 => PricingRule(quantity: 3, product_id: 2, price: 250)]
 * we sort it by key which is the quantity of pricing rules, in descending order.
 * because in our calculating algorithm (the below loop) we need to iterate this array by the descending order of quantities of pricing rules;
 * */
        krsort($pricingRules);
        $totalPrice = 0;
        foreach($pricingRules as $pricingRule) {
            /** @var PricingRuleDTO $pricingRule */
            if ($quantity >= $pricingRule->getQuantity()) {
                $totalPrice += intval($quantity/$pricingRule->getQuantity()) * $pricingRule->getPrice();
                $quantity = $quantity%$pricingRule->getQuantity();
            }
        }

        return $totalPrice;
    }

    public function getProductsPricingRules(array $productIdQuantityMap): array
    {
        $productIds = [];
        $specialPrices = $this->specialPriceModel;

        foreach ($productIdQuantityMap as $productId => $quantity) {
/*
 * We only need special prices of products which their rule quantities are lower than our product quantity in the cart,
 * And for performance concerns We only get special prices that we need them.
 * So I have applied this logic by the below query builder
*/
            $specialPrices = $specialPrices
                ->with('product')
                ->orWhereRaw("product_id = ? and quantity <= ?", [$productId, $quantity]);
            $productIds[] = $productId;
        }
        $specialPrices = $specialPrices->get();

        $products = $this->productModel
            ->with('specialPrices')
            ->whereIn('id', $productIds)
            ->get();


        $productsPricingRuleDTOs = [];

        foreach ($products as $product) {
            $productPricingRuleDTO = new PricingRuleDTO($product->id, 1, $product->unit_price);
            $productsPricingRuleDTOs[$product->id][1] = $productPricingRuleDTO;
        }

        foreach ($specialPrices as $specialPrice) {
            if (!isset($productsPricingRuleDTOs[$specialPrice->product_id])) {
                $productsPricingRuleDTOs[$specialPrice->product_id][1] = $specialPrice->product->unit_price;
            }
            $productPricingRuleDTO = new PricingRuleDTO($specialPrice->product_id, $specialPrice->quantity, $specialPrice->price);
            $productPricingRuleDTOs[$specialPrice->quantity] = $productPricingRuleDTO;

            $productsPricingRuleDTOs[$specialPrice->product_id][$specialPrice->quantity] = $productPricingRuleDTO;
        }

        return $productsPricingRuleDTOs;
    }
}
