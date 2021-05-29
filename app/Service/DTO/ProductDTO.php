<?php


namespace App\Service\DTO;


class ProductDTO
{
    /** @var int $id */
    private $id;

    /** @var string $title */
    private $title;

    /** @var float $unitPrice */
    private $unitPrice;

    public function __construct(?int $id, string $title, float $unitPrice)
    {
        $this->id = $id;
        $this->title = $title;
        $this->unitPrice = $unitPrice;
    }

    /**
     * @return int
     */
    public function getId(): ?int
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return float
     */
    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    /**
     * @param float $unitPrice
     */
    public function setUnitPrice(float $unitPrice): void
    {
        $this->unitPrice = $unitPrice;
    }
}