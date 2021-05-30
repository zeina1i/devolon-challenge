<?php


namespace App\Service;


use App\Exceptions\EntityNotFoundException;
use App\Exceptions\ExistsException;
use App\Exceptions\NotFoundException;
use App\Model\Product;
use App\Model\SpecialPrice;
use App\Service\DTO\SpecialPriceDTO;
use App\Service\Transformer\SpecialPriceTransformer;

class SpecialPriceService
{
    private $specialPriceModel;
    private $specialPriceTransformer;
    private $productModel;

    public function __construct(
        SpecialPrice $specialPriceModel,
        SpecialPriceTransformer $specialPriceTransformer,
        Product $productModel)
    {
        $this->specialPriceModel = $specialPriceModel;
        $this->specialPriceTransformer = $specialPriceTransformer;
        $this->productModel = $productModel;
    }

    public function add(SpecialPriceDTO $specialPriceDTO) : SpecialPriceDTO
    {
        $product = $this->productModel->find($specialPriceDTO->getProductId());
        if ($product == null) {
            throw new EntityNotFoundException("products", $specialPriceDTO->getProductId());
        }

        $specialPrice = $this->specialPriceModel
            ->where(['product_id' => $specialPriceDTO->getProductId(), 'quantity' => $specialPriceDTO->getQuantity()])
            ->first();
        if ($specialPrice != null) {
            throw new ExistsException(sprintf(
                "entity special_price with product_id: %s and quantity: %s currently exists",
                $specialPriceDTO->getProductId(),
                $specialPriceDTO->getQuantity()));
        }
        $specialPrice = $this->specialPriceTransformer->transformDTOToEntity($specialPriceDTO);
        $specialPrice->save();

        return $this->specialPriceTransformer->transformEntityToDTO($specialPrice);
    }

    public function update(SpecialPriceDTO $specialPriceDTO) : SpecialPriceDTO
    {
        $specialPrice = $this->specialPriceTransformer->transformDTOToEntity($specialPriceDTO);
        $specialPrice->save();

        return $this->specialPriceTransformer->transformEntityToDTO($specialPrice);
    }

    public function delete(int $productId, int $quantity) : bool
    {
        $recordsAffected =  $this->specialPriceModel
            ->where(['product_id' => $productId, 'quantity' => $quantity])
            ->delete();
        if ($recordsAffected == 0) {
            throw new NotFoundException(sprintf(
                "entity special_price with product_id: %s and quantity: %s not found",
                $productId,
                $quantity));
        }

        return true;
    }

    public function getProductSpecialPrices(int $productId) : array
    {
        $specialPrices = $this->specialPriceModel
            ->where(['product_id' => $productId])
            ->delete();

        $specialPriceDTOs = [];
        foreach ($specialPrices as $specialPrice) {
            $specialPriceDTOs[] = $this->specialPriceTransformer->transformEntityToDTO($specialPrice);
        }

        return $specialPriceDTOs;
    }
}