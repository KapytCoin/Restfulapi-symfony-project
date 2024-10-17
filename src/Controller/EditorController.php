<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\EditorService;
use App\Attribute\RequestBody;
use App\Model\Editor\CreateProductRequest;
use Symfony\Component\HttpFoundation\Request;
use App\Model\ProductListResponse;
use App\Model\PublishProductRequest;

class EditorController extends AbstractController
{
    public function __construct(private EditorService $editorService)
    {
    }

    #[Route(path: '/api/v1/editor/products', methods: ['GET'])]
    public function products(): Response
    {
        return $this->json($this->editorService->getProducts());
    }

    #[Route(path: '/api/v1/editor/product/{id}/publish', methods: ['POST'])]
    public function publish(int $id, #[RequestBody] PublishProductRequest $request): Response
    {
        $this->editorService->publish($id, $request);

        return $this->json(null);
    }

    #[Route(path: '/api/v1/editor/product/{id}/unpublish', methods: ['POST'])]
    public function unpublish(int $id): Response
    {
        $this->editorService->unpublish($id);

        return $this->json(null);
    }

    #[Route(path: '/api/v1/editor/product', methods: ['POST'])]
    public function createProduct(#[RequestBody] CreateProductRequest $request): Response
    {
        return $this->json($this->editorService->createProduct($request));
    }

    #[Route(path: '/api/v1/editor/product/{id}', methods: ['DELETE'])]
    public function deleteProudct(int $id): Response
    {
        $this->editorService->deleteProduct($id);

        return $this->json(null);
    }
}
