<?php

namespace App\Security;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\PayloadAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class JwtUserProvider implements PayloadAwareUserProviderInterface
{
    public function __construct(private readonly UsersRepository $userRepository)
    {
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->getUser('email', $identifier);
    }

    public function loadUserByIdentifierAndPayload(string $identifier, array $payload): UserInterface
    {
        return $this->getUser('id', $payload['id']);
    }

    // deprecated
    public function loadUserByUsernameAndPayload(string $username, array $payload): ?UserInterface
    {
        return null;
    }

    // deprecated
    public function loadUserByUsername(string $username): ?UserInterface
    {
        return null;
    }

    // not needed
    public function refreshUser(UserInterface $user): UserInterface
    {
        throw new UnsupportedUserException();
    }

    public function supportsClass(string $class): bool
    {
        return Users::class === $class || is_subclass_of($class, Users::class);
    }

    private function getUser(string $key, mixed $value): UserInterface
    {
        $user = $this->userRepository->findOneBy([$key => $value]);
        if (null === $user) {
            $e = new UserNotFoundException('User with id '.json_encode($value, JSON_THROW_ON_ERROR).' not found.');
            $e->setUserIdentifier(json_encode($value, JSON_THROW_ON_ERROR));

            throw $e;
        }

        return $user;
    }
}