<?php

declare(strict_types=1);

namespace App\Application\UseCase\AuthInGame;

use App\Application\UseCase\AuthInGame\Exception\UserDoesntHaveAccessToGameSessionException;
use App\Application\Service\Security\UserProvider\UserProviderInterface;
use App\Domain\Repository\GameSessionRepositoryInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AuthInGameUseCase implements AuthInGameUseCaseInterface
{
    public function __construct(
        private readonly UserProviderInterface $userProvider,
        private readonly JWTTokenManagerInterface $jWTTokenManager,
        private readonly GameSessionRepositoryInterface $gameSessionRepository,
    ) {
    }

    public function __invoke(AuthInGameDto $dto): AuthInGameResult
    {
        $user = $this->userProvider->getUserById($dto->userId);

        $game = $this->gameSessionRepository->findById($dto->gameSessionId);

        if (false === $game->containsUserId($dto->userId)) {
            throw new UserDoesntHaveAccessToGameSessionException();
        }

        $accessToken = $this->jWTTokenManager->createFromPayload($user, [
            'gameSessionId' => $dto->gameSessionId,
            'userId' => $dto->userId,
        ]);

        return new AuthInGameResult($accessToken);
    }
}
