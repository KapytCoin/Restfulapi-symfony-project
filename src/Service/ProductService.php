<?php

namespace App\Service;

use App\Exception\ProductNotFoundException;
use App\Mapper\ProductMapper;
use App\Repository\ProductRepository;
use App\Repository\ProductCategoryRepository;
use App\Model\ProductListResponse;
use App\Exception\ProductCategoryNotFoundException;
use App\Model\ProductListItem;
use App\Entity\Product;
use App\Model\ProductDetails;
use App\Entity\ProductFormat;
use App\Entity\ProductToProductFormat;
use App\Model\ProductCategory as ProductCategoryModel;
use App\Entity\ProductCategory;
use App\Service\RatingService;

class ProductService
{
    public function __construct(
    private ProductRepository $productRepository, 
    private ProductCategoryRepository $productCategoryRepository, 
    private RatingService $ratingService)
    {
    }

    public function getProductsByCategory(int $categoryId): ProductListResponse
    {
        if (!$this->productCategoryRepository->existsById($categoryId)) {
            throw new ProductCategoryNotFoundException();
        }

        return new ProductListResponse(array_map(
            fn (Product $product) =>ProductMapper::map($product, new ProductListItem()),
            $this->productRepository->findPublishedProductsByCategoryId($categoryId)
        ));
    }

    public function getProductsById(int $id): ProductDetails
    {
        throw new ProductNotFoundException();
    }

    public function getProductById(int $id): ProductDetails
    {
        $product = $this->productRepository->getPublishedById($id);

        $categories = $product->getCategories()
            ->map(fn (ProductCategory $productCategory) => new ProductCategoryModel(
                $productCategory->getId(), $productCategory->getTitle(), $productCategory->getSlug()
            ));

        $rating = $this->ratingService->calcReviewRatingForProduct($id);

        return ProductMapper::map($product, new ProductDetails())
            ->setRating($rating->getRating())
            ->setReviews($rating->getTotal())
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
        )->toArray();        
    }
}