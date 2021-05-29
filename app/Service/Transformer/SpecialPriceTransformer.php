<?php


namespace App\Service\Transformer;


use App\Exceptions\EntityNotFoundException;
use App\Model\SpecialPrice;
use App\Service\DTO\SpecialPriceDTO;

class SpecialPriceTransformer
{
    private $specialPriceModel;

    public function __construct(
        SpecialPrice $specialPriceModel
    )
    {
        $this->specialPriceModel = $specialPriceModel;
    }

    public function transformEntityToDTO(SpecialPrice $specialPrice) : SpecialPriceDTO
    {
        return new SpecialPriceDTO(
            $specialPrice->id,
            $specialPrice->product_id,
            $specialPrice->quantity,
            $specialPrice->price);
    }

    public function transformDTOToEntity(SpecialPriceDTO $specialPriceDTO) : SpecialPrice
    {
        if ($specialPriceDTO->getId() > 0) {
            $specialPrice = $this->specialPriceModel->find($specialPriceDTO->getId());
            if ($specialPrice == null) {
                throw new EntityNotFoundException("special_prices", $specialPriceDTO->getId());
            }
        } else {
            $specialPrice = new $this->specialPriceModel;
        }

        $specialPrice->product_id = $specialPriceDTO->getProductId();
        $specialPrice->price = $specialPriceDTO->getPrice();
        $specialPrice->quantity = $specialPriceDTO->getQuantity();

        return $specialPrice;
    }
}