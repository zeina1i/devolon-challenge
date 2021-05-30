<?php


namespace App\Service\DTO;


class ItemPricingDetailDTO implements \JsonSerializable
{
    private $productId;
    private $pricingRule;
    private $totalQuantity;
    private $totalPrice;

    public function __construct(
        int $productId,
        PricingRuleDTO $pricingRule,
        int $totalQuantity,
        float $totalPrice
    )
    {
        $this->productId = $productId;
        $this->pricingRule = $pricingRule;
        $this->totalQuantity = $totalQuantity;
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param mixed $productId
     */
    public function setProductId($productId): void
    {
        $this->productId = $productId;
    }

    /**
     * @return mixed
     */
    public function getPricingRule()
    {
        return $this->pricingRule;
    }

    /**
     * @param mixed $pricingRule
     */
    public function setPricingRule($pricingRule): void
    {
        $this->pricingRule = $pricingRule;
    }

    /**
     * @return mixed
     */
    public function getTotalQuantity()
    {
        return $this->totalQuantity;
    }

    /**
     * @param mixed $totalQuantity
     */
    public function setTotalQuantity($totalQuantity): void
    {
        $this->totalQuantity = $totalQuantity;
    }

    /**
     * @return mixed
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * @param mixed $totalPrice
     */
    public function setTotalPrice($totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'product_id' => $this->productId,
            'pricing_rule' => $this->pricingRule,
            'total_quantity' => $this->totalQuantity,
            'total_price' => $this->totalPrice,
        ];
    }
}