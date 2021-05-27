<?php


namespace App\Service\DTO;


class CartItemDTO implements \JsonSerializable
{
    /** @var int $id */
    private $id;

    /** @var int $cartId */
    private $cartId;

    /** @var int $productId */
    private $productId;

    /** @var string $productTitle */
    private $productTitle;

    /** @var int $quantity */
    private $quantity;

    /** @var float $payablePrice */
    private $payablePrice;

    /** @var array $detailedPrice */
    private $detailedPrice;

    /** @var \DateTime $updatedAt */
    private $updatedAt;

    /** @var \DateTime $createdAt */
    private $createdAt;

    public function __construct(
        int $id,
        int $cartId,
        int $productId,
        string $productTitle,
        int $quantity,
        float $payablePrice,
        array $detailedPrice,
        \DateTime $updatedAt,
        \DateTime $createdAt
    )
    {
        $this->id = $id;
        $this->cartId = $cartId;
        $this->productId = $productId;
        $this->productTitle = $productTitle;
        $this->quantity = $quantity;
        $this->payablePrice = $payablePrice;
        $this->detailedPrice = $detailedPrice;
        $this->updatedAt = $updatedAt;
        $this->createdAt = $createdAt;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getCartId(): int
    {
        return $this->cartId;
    }

    /**
     * @param int $cartId
     */
    public function setCartId(int $cartId): void
    {
        $this->cartId = $cartId;
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
     * @return string
     */
    public function getProductTitle(): string
    {
        return $this->productTitle;
    }

    /**
     * @param string $productTitle
     */
    public function setProductTitle(string $productTitle): void
    {
        $this->productTitle = $productTitle;
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
    public function getPayablePrice(): float
    {
        return $this->payablePrice;
    }

    /**
     * @param float $payablePrice
     */
    public function setPayablePrice(float $payablePrice): void
    {
        $this->payablePrice = $payablePrice;
    }

    /**
     * @return array
     */
    public function getDetailedPrice(): array
    {
        return $this->detailedPrice;
    }

    /**
     * @param array $detailedPrice
     */
    public function setDetailedPrice(array $detailedPrice): void
    {
        $this->detailedPrice = $detailedPrice;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
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
            'id' => $this->id,
            'cart_id' => $this->cartId,
            'product_id' => $this->productId,
            'product_title' => $this->productTitle,
            'quantity' => $this->quantity,
            'payable_price' => $this->payablePrice,
            'detailed_price' => $this->detailedPrice,
            'updated_at' => $this->updatedAt,
            'created_at' => $this->createdAt,
        ];
    }
}