<?php

namespace App\Service;

use App\Entity\Product;
use App\Model\RecommendedProduct;
use App\Model\RecommendedProductListResponse;
use App\Repository\ProductRepository;
use App\Service\Recommendation\RecommendationApiService;
use App\Service\Recommendation\Model\RecommendationItem;

class RecommendationService
{
    private const MAX_DESCRIPTION_LENGTH = 150;

    public function __construct(
        private ProductRepository $productRepository,
        private RecommendationApiService $recommendationApiService)
    {
    }

    public function getRecommendationsByProductId(int $id): RecommendedProductListResponse
    {
        $ids = array_map(
            fn (RecommendationItem $item) => $item->getId(),
            $this->recommendationApiService->getRecommendationsByProductId($id)->getRecommendations()
        );

        return new RecommendedProductListResponse(
            array_map([$this, 'map'], $this->productRepository->findProductsByIds($ids))
        );
    }

    private function map(Product $product): RecommendedProduct
    {
        $description = $product->getDescription();
        $description = strlen($description) > self::MAX_DESCRIPTION_LENGTH ? substr(
            $description, 0, self::MAX_DESCRIPTION_LENGTH- 3).'...' : $description;

        return (new RecommendedProduct())
            ->setId($product->getId())
            ->setImage($product->getImage())
            ->setSlug($product->getSlug())
            ->setTitle($product->getTitle())
            ->setShortDescription($description);
    }
}