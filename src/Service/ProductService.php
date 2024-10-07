<?php

namespace App\Service;

use App\Repository\ProductRepository;
use App\Repository\ProductCategoryRepository;
use App\Model\ProductListResponse;
use App\Exception\ProductCategoryNotFoundException;
use App\Model\ProductListItem;
use App\Entity\Product;

class ProductService
{
    public function __construct(private ProductRepository $productRepository, private ProductCategoryRepository $productCategoryRepository)
    {
    }

    public function getProductsByCategory(int $categoryId): ProductListResponse
    {
        if (!$this->productCategoryRepository->existsById($categoryId)) {
            throw new ProductCategoryNotFoundException();
        }

        return new ProductListResponse(array_map(
            [$this, 'map'],
            $this->productRepository->findProductsByCategoryId($categoryId)
        ));
    }

    private function map(Product $product): ProductListItem
    {
        return (new ProductListItem())
            ->setId($product->getId())
            ->setTitle($product->getTitle())
            ->setSlug($product->getSlug())
            ->setImage($product->getImage())
            ->setAuthors($product->getAuthors())
            ->setMeap($product->isMeap())
            ->setPublicationDate($product->getPublicationDate()->getTimestamp());
    }
}