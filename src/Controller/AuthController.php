<?php

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Model\SignUpRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\SignUpService;

class AuthController extends AbstractController
{
    public function __construct(private SignUpService $signUpService)
    {
    }

    #[Route('/api/v1/auth/signUp', methods: ['POST'])]
    public function signUp(#[RequestBody] SignUpRequest $signUpRequest): Response
    {
        return $this->signUpService->signUp($signUpRequest);
    }
}
