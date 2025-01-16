<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Security\UserProvider;

use App\Application\Service\Security\UserProvider\ApiClientUserProviderInterface;
use App\Domain\Entity\ApiClientUser;
use App\Domain\Repository\ApiClientUserRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiClientUserProvider implements ApiClientUserProviderInterface
{
    public function __construct(
        private readonly ApiClientUserRepositoryInterface $apiClientUserRepository,
    ) {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        throw new UnsupportedUserException();
    }

    public function supportsClass(string $class): bool
    {
        return ApiClientUser::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->apiClientUserRepository->findByApiKey($identifier);

        if (null !== $user) {
            return $user;
        }

        throw new UserNotFoundException();
    }
}
