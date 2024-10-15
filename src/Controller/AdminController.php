<?php

namespace App\Controller;

use App\Service\RoleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    public function __construct(private RoleService $roleService)
    {
    }

    #[Route(path: '/api/v1/admin/grantEditor/{userId}', methods: ['POST'])]
    public function grantEditor(int $userId): Response
    {
        $this->roleService->grantEditor($userId);

        return $this->json(null);
    }
}