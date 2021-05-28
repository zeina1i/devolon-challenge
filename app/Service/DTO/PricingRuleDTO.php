<?php


namespace App\Service\DTO;


class PricingRuleDTO
{
    /** @var int $productId */
    private $productId;

    /** @var int $quantity */
    private $quantity;

    /** @var float $price */
    private $price;

    public function __construct(
        int $productId, int $quantity, float $price
    )
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}