<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UsersRepository;

class RoleService
{
    public function __construct(private UsersRepository $usersRepossitory, private EntityManagerInterface $em)
    {
    }

    public function grantAdmin(int $userId): void
    {
        $this->grantRole($userId, 'ROLE_ADMIN');
    }

    public function grantEditor(int $userId): void
    {
        $this->grantRole($userId, 'ROLE_EDITOR');
    }

    private function grantRole(int $userId, string $role): void
    {
        $user = $this->usersRepossitory->getUser($userId);
        $user->setRoles([$role]);

        $this->em->flush();
    }
}