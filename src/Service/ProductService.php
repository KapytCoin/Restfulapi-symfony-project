<?php

namespace App\Service;

use App\Exception\ProductNotFoundException;
use App\Repository\ProductRepository;
use App\Repository\ProductCategoryRepository;
use App\Model\ProductListResponse;
use App\Exception\ProductCategoryNotFoundException;
use App\Model\ProductListItem;
use App\Entity\Product;
use App\Model\ProductDetails;
use App\Repository\ReviewRepository;
use App\Entity\ProductFormat;
use App\Entity\ProductToProductFormat;
use App\Model\ProductCategory as ProductCategoryModel;
use App\Entity\ProductCategory;

class ProductService
{
    public function __construct(
    private ProductRepository $productRepository, 
    private ProductCategoryRepository $productCategoryRepository, 
    private ReviewRepository $reviewRepository)
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

    public function getProductById(int $id): ProductDetails
    {
        throw new ProductNotFoundException();
    }

    public function getProductsById(int $id): ProductDetails
    {
        $product = $this->productRepository->getById($id);
        $reviews = $this->reviewRepository->countByProductId($id);
        $ratingSum = $this->reviewRepository->getProductTotalRatingSum($id);
        $categories = $product->getCategories()
            ->map(fn (ProductCategory $productCategory) => new ProductCategoryModel(
                $productCategory->getId(), $productCategory->getTitle(), $productCategory->getSlug()
            ));

        return (new ProductDetails())
            ->setId($product->getId())
            ->setTitle($product->getTitle())
            ->setSlug($product->getSlug())
            ->setImage($product->getImage())
            ->setPublicationDate($product->getPublicationDate()->getTimestamp())
            ->setRating($ratingSum / $reviews)
            ->setReviews($reviews)
            ->setFormats($this->mapFormats($product->getFormats()))
            ->setCategories($categories->toArray());
    }

    public function mapFormats($formats): array
    {
        return $formats->map(fn (ProductToProductFormat $formatJoin) => (new ProductFormat())
            ->setId($formatJoin->getFormat()->getId())
            ->setTitle($formatJoin->getFormat()->getTitle())
            ->setDescription($formatJoin->getFormat()->getDescription())
            ->setComment($formatJoin->getPrice())
            ->setPrice($formatJoin->getPrice())
            ->setDiscountPercent($formatJoin->getDiscountPercent())
        );        
    }

    private function map(Product $product): ProductListItem
    {
        return (new ProductListItem())
            ->setId($product->getId())
            ->setTitle($product->getTitle())
            ->setSlug($product->getSlug())
            ->setImage($product->getImage())
            ->setPublicationDate($product->getPublicationDate()->getTimestamp());
    }
}