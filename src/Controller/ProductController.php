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

    #[Route('api/v1/category/{id}/products', name: 'app_productByCategory')]
    public function productsByCategory(int $id): Response
    {
        return $this->json($this->productService->getProductsByCategory($id));
    }
}
