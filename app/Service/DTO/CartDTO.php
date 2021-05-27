<?php


namespace App\Service\DTO;


use DateTime;

class CartDTO implements \JsonSerializable
{
    /** @var int $id */
    private $id;

    /** @var string $cartStatus */
    private $cartStatus;

    /** @var array $cartItems */
    private $cartItems;

    /** @var float $payablePrice */
    private $payablePrice;

    /** @var \DateTime $closedAt */
    private $closedAt;

    /** @var \DateTime $updatedAt */
    private $updatedAt;

    /** @var \DateTime $createdAt */
    private $createdAt;

    public function __construct(
        ?int $id,
        string $cartStatus,
        ?array $cartItems,
        float $payablePrice,
        ?DateTime $closedAt,
        ?DateTime $updatedAt,
        ?DateTime $createdAt
    ) {
        $this->id = $id;
        $this->cartStatus = $cartStatus;
        $this->cartItems = $cartItems;
        $this->payablePrice = $payablePrice;
        $this->closedAt = $closedAt;
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
     * @return string
     */
    public function getCartStatus(): string
    {
        return $this->cartStatus;
    }

    /**
     * @param string $cartStatus
     */
    public function setCartStatus(string $cartStatus): void
    {
        $this->cartStatus = $cartStatus;
    }

    /**
     * @return array
     */
    public function getCartItems(): array
    {
        return $this->cartItems;
    }

    /**
     * @param array $cartItems
     */
    public function setCartItems(array $cartItems): void
    {
        $this->cartItems = $cartItems;
    }

    /**
     * @return \DateTime
     */
    public function getClosedAt(): \DateTime
    {
        return $this->closedAt;
    }

    /**
     * @param \DateTime $closedAt
     */
    public function setClosedAt(\DateTime $closedAt): void
    {
        $this->closedAt = $closedAt;
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
            'cart_status' => $this->cartStatus,
            'cart_items' => $this->cartItems,
            'payable_price' => $this->payablePrice,
            'closed_at' => $this->closedAt,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
