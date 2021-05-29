<?php


namespace App\Service\Transformer;


use App\Exceptions\EntityNotFoundException;
use App\Model\Product;
use App\Service\DTO\ProductDTO;

class ProductTransformer
{
    private $productModel;

    public function __construct(Product $product)
    {
        $this->productModel = $product;
    }

    public function transformEntityToDTO(Product $product) : ProductDTO
    {
        return new ProductDTO(
            $product->id,
            $product->title,
            $product->unit_price
        );
    }

    public function transformDTOToEntity(ProductDTO $productDTO) : Product
    {
        if ($productDTO->getId() > 0) {
            $product = $this->productModel->find($productDTO->getId());
            if ($product == null) {
                throw new EntityNotFoundException("product", $productDTO->getId());
            }
        } else {
            $product = new $this->productModel;
        }
        $product->title = $productDTO->getTitle();
        $product->unit_price = $productDTO->getUnitPrice();

        return $product;
    }
}