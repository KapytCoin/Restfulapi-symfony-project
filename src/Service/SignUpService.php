<?php

namespace App\Service;

use App\Model\IdResponse;
use App\Model\SignUpRequest;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Exception\UserAlreadyExistsException;
use App\Entity\Users;

class SignUpService
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private UsersRepository $userRepository,
        private EntityManagerInterface $em)
    {
    }

    public function signUp(SignUpRequest $signUpRequest): IdResponse
    {
        if ($this->userRepository->existsByEmail($signUpRequest->getEmail())) {
            throw new UserAlreadyExistsException();
        }

        $user = (new Users())
            ->setFirstName($signUpRequest->getFirstName())
            ->setLastName($signUpRequest->getLastName())
            ->setEmail($signUpRequest->getEmail());

        $user->setPassword($this->hasher->hashPassword($user, $signUpRequest->getPassword()));

        $this->em->persist($user);
        $this->em->flush();

        return new IdResponse($user->getId());
    }
}