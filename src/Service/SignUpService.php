<?php

namespace App\Service;

use App\Model\IdResponse;
use App\Model\SignUpRequest;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Exception\UserAlreadyExistsException;
use App\Entity\Users;

class SignUpService
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private UsersRepository $userRepository,
        private EntityManagerInterface $em,
        private AuthenticationSuccessHandler $successHandler)
    {
    }

    public function signUp(SignUpRequest $signUpRequest): Response
    {
        if ($this->userRepository->existsByEmail($signUpRequest->getEmail())) {
            throw new UserAlreadyExistsException();
        }

        $user = (new Users())
            ->setRoles(['ROLE_USER'])
            ->setFirstName($signUpRequest->getFirstName())
            ->setLastName($signUpRequest->getLastName())
            ->setEmail($signUpRequest->getEmail());

        $user->setPassword($this->hasher->hashPassword($user, $signUpRequest->getPassword()));

        $this->em->persist($user);
        $this->em->flush();

        return $this->successHandler->handleAuthenticationSuccess($user);
    }
}