<?php


namespace App\Service;


use App\Exceptions\EntityNotFoundException;
use App\Model\Product;
use App\Service\DTO\ProductDTO;
use App\Service\Transformer\ProductTransformer;

class ProductService
{
    private $productModel;
    private $productTransformer;

    public function __construct(Product $productModel, ProductTransformer $productTransformer)
    {
        $this->productModel = $productModel;
        $this->productTransformer = $productTransformer;
    }

    public function get(int $productId) : ProductDTO
    {
        $product = $this->productModel->find($productId);
        if ($product == null) {
            throw new EntityNotFoundException('products', $productId);
        }

        return $this->productTransformer->transformEntityToDTO($product);

    }

    public function list(int $offset, int $limit) : array
    {
        $products = $this->productModel
            ->skip($offset)->take($limit)->get();

        $productDTOs = [];
        foreach ($products as $product) {
            $productDTOs[] = $this->productTransformer->transformEntityToDTO($product);
        }

        return $productDTOs;
    }

    public function add(ProductDTO $productDTO) : ProductDTO
    {
        $product = $this->productTransformer->transformDTOToEntity($productDTO);
        $product->save();

        return $this->productTransformer->transformEntityToDTO($product);
    }

    public function update(ProductDTO $productDTO) : ProductDTO
    {
        $product = $this->productTransformer->transformDTOToEntity($productDTO);
        $product->save();

        return $this->productTransformer->transformEntityToDTO($product);
    }

    public function delete(int $productId) : bool
    {
        $recordsAffected =  $this->productModel
            ->where(['id' => $productId])
            ->delete();
        if($recordsAffected == 0) {
            throw new EntityNotFoundException("product", $productId);
        }

        return true;
    }
}