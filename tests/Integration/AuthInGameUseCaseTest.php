<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Application\UseCase\AuthInGame\AuthInGameDto;
use App\Application\UseCase\AuthInGame\AuthInGameUseCase;
use App\Application\UseCase\AuthInGame\Exception\UserDoesntHaveAccessToGameSessionException;
use App\Domain\Entity\GameSession;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class AuthInGameUseCaseTest extends KernelTestCase
{
    public function testSuccessfulAuth(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $userId = 1;

        $gameSession = new GameSession(1, [$userId]);

        /**  @var EntityManagerInterface $entityManager */
        $entityManager = $container->get(EntityManagerInterface::class);

        $entityManager->persist($gameSession);
        $entityManager->flush();

        $gameSessionId = $gameSession->getId();

        $authUserUseCase = $container->get(AuthInGameUseCase::class);
        $dto = new AuthInGameDto($userId, $gameSessionId);

        $result = ($authUserUseCase)($dto);
        
        $accessToken = $result->accessToken;

        $this->assertGreaterThan(0, strlen($accessToken));
        
        /**  @var JWTTokenManagerInterface $jwtTokenManager */
        $jwtTokenManager = $container->get(JWTTokenManagerInterface::class);

        $payload = $jwtTokenManager->parse($accessToken);

        $this->assertArrayHasKey('gameSessionId', $payload);
        $this->assertEquals($gameSessionId, $payload['gameSessionId']);
    }

    public function testDoesntHaveAccess(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $userId = 1;

        $gameSession = new GameSession(1, [2]);

        /**  @var EntityManagerInterface $entityManager */
        $entityManager = $container->get(EntityManagerInterface::class);

        $entityManager->persist($gameSession);
        $entityManager->flush();

        $gameSessionId = $gameSession->getId();

        $authUserUseCase = $container->get(AuthInGameUseCase::class);
        $dto = new AuthInGameDto($userId, $gameSessionId);

        $this->expectException(UserDoesntHaveAccessToGameSessionException::class);

        ($authUserUseCase)($dto);
    }    
}