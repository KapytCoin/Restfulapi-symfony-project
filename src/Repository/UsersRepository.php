<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Exception\UserNotFoundException;

/**
 * @extends ServiceEntityRepository<Users>
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    public function existsByEmail(string $email): bool
    {
        return null !== $this->findOneBy(['email' => $email]);
    }

    public function getUser(int $userId): Users
    {
        $user = $this->find($userId);
        if (null === $user) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
