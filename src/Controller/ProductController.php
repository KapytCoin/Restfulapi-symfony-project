<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Exception\ProductCategoryNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Service\ProductService;

class ProductController extends AbstractController
{
    public function __construct(private ProductService $productService)
    {
    }

    #[Route('/api/v1/category/{id}/products', name: 'app_productByCategory')]
    public function productsByCategory(int $id): Response
    {
        try {
            return $this->json($this->productService->getProductsByCategory($id));
        } catch (ProductCategoryNotFoundException $exception) {
            throw new HttpException($exception->getCode(), $exception->getMessage());
        }
    }
}
