<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ProductService;

class ProductController extends AbstractController
{
    public function __construct(private ProductService $productService)
    {
    }

    /**
     * @OA\Response(
     *      response=200,
     *      description="Returns products inside a category",
     *      @Model(type=ProductListResponse::class)
     * )
     * @OA\Response(
     *      response=404,
     *      description="prodcut category not found",
     *      @Model(type=ErrorResponse::class)
     * )
     */
    #[Route('api/v1/category/{id}/products', name: 'app_productByCategory', methods: ['GET'])]
    public function productsByCategory(int $id): Response
    {
        return $this->json($this->productService->getProductsByCategory($id));
    }
}
