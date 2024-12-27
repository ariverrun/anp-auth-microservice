<?php

declare(strict_types=1);

namespace App\Application\UseCase\AuthUser;

use Doctrine\ORM\EntityManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AuthUserUseCase implements AuthUserUseCaseInterface
{
    /**
     * @param UserProviderInterface<UserInterface> $userProvider
     */
    public function __construct(
        private readonly UserProviderInterface $userProvider,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly JWTTokenManagerInterface $jWTTokenManager,
        private readonly RefreshTokenGeneratorInterface $refreshTokenGenerator,
        private readonly int $userRefreshTokenTtl,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(AuthUserDto $dto): AuthUserResult
    {
        $user = $this->userProvider->loadUserByIdentifier($dto->username);

        if ($user instanceof PasswordAuthenticatedUserInterface) {
            if (false === $this->userPasswordHasher->isPasswordValid($user, $dto->password)) {
                throw new BadCredentialsException();
            }
        }

        $accessToken = $this->jWTTokenManager->create($user);

        $refreshToken = $this->refreshTokenGenerator->createForUserWithTtl($user, $this->userRefreshTokenTtl);

        $this->entityManager->persist($refreshToken);
        $this->entityManager->flush();

        return new AuthUserResult($accessToken, $refreshToken->getRefreshToken());
    }
}
