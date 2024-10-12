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
use App\Repository\ReviewRepository;
use App\Entity\ProductFormat;
use App\Entity\ProductToProductFormat;
use App\Model\ProductCategory as ProductCategoryModel;
use App\Entity\ProductCategory;
use App\Service\RatingService;
use App\Service\Recommendation\Model\RecommendationItem;
use App\Service\Recommendation\RecommendationService;
use Exception;
use Psr\Log\LoggerInterface;

class ProductService
{
    public function __construct(
    private ProductRepository $productRepository, 
    private ProductCategoryRepository $productCategoryRepository, 
    private ReviewRepository $reviewRepository,
    private RatingService $ratingService,
    private RecommendationService $recommendationService,
    private LoggerInterface $logger)
    {
    }

    public function getProductsByCategory(int $categoryId): ProductListResponse
    {
        if (!$this->productCategoryRepository->existsById($categoryId)) {
            throw new ProductCategoryNotFoundException();
        }

        return new ProductListResponse(array_map(
            fn (Product $product) =>ProductMapper::map($product, new ProductListItem()),
            $this->productRepository->findProductsByCategoryId($categoryId)
        ));
    }

    public function getProductsById(int $id): ProductDetails
    {
        throw new ProductNotFoundException();
    }

    public function getProductById(int $id): ProductDetails
    {
        $product = $this->productRepository->getById($id);
        $reviews = $this->reviewRepository->countByProductId($id);
        $recommendations = [];

        $categories = $product->getCategories()
            ->map(fn (ProductCategory $productCategory) => new ProductCategoryModel(
                $productCategory->getId(), $productCategory->getTitle(), $productCategory->getSlug()
            ));

            try {
                $recommendations = $this->getRecommendations($id);
            } catch (Exception $ex) {
                $this->logger->error('error while fetching recommendations', [
                    'exception' => $ex->getMessage(),
                    'productId' => $id,
                ]);
            }

        return ProductMapper::map($product, new ProductDetails())
            ->setRating($this->ratingService->calcReviewRatingForProduct($id, $reviews))
            ->setReviews($reviews)
            ->setRecommendations($recommendations)
            ->setFormats($this->mapFormats($product->getFormats()))
            ->setCategories($categories->toArray());
    }

    public function getRecommendations(int $productId): array
    {
        $ids = array_map(
            fn (RecommendationItem $item) => $item->getId(),
            $this->recommendationService->getRecommendationsByProductId($productId)->getRecommendations()
        );

        return array_map([ProductMapper::class, 'mapRecommended'], $this->productRepository->findProductsByIds($ids));
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