<?php


namespace App\Service\DTO;


class PricingRuleDTO implements \JsonSerializable
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

    public function __toString()
    {
        return sprintf('price: %s for %s of product_id: %s', $this->price, $this->quantity, $this->productId);
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
            'quantity' => $this->quantity,
            'price' => $this->price,
        ];
    }
}