<?php

namespace App\Controller;

use App\Service\ProductCategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class ProductCategoryController extends AbstractController
{
    public function __construct(private ProductCategoryService $productCategoryService)
    {
    }

    /**
     * @OA\Response(
     *      response=200,
     *      description="Returns product categories",
     *      @Model(type=ProductCategoryListResponse::class)
     * )
     */
    #[Route('/api/v1/product/categories', name: 'app_categories', methods: ['GET'])]
    public function categories(): Response
    {
        return $this->json($this->productCategoryService->getCategories());
    }
}
