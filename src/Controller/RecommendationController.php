<?php

namespace App\Controller;

use App\Service\RecommendationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecommendationController extends AbstractController
{
    public function __construct(private RecommendationService $recommendationService)
    {
    }

    #[Route(path: '/api/v1/product/{id}/recommendations')]
    public function recommendationsByProductId(int $id): Response
    {
        return $this->json($this->recommendationService->getRecommendationsByProductId($id));
    }
}