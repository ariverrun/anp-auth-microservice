<?php

declare(strict_types=1);

namespace App\Application\UseCase\CreateGameSession;

use App\Domain\Entity\GameSession;
use Doctrine\ORM\EntityManagerInterface;

class CreateGameSessionUseCase implements CreateGameSessionUseCaseInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(CreateGameSessionDto $dto): CreateGameSessionResult
    {
        $gameSession = new GameSession($dto->gameId, array_unique($dto->userIds));

        $this->entityManager->persist($gameSession);
        $this->entityManager->flush();

        return new CreateGameSessionResult(
            $gameSession->getId(),
        );
    }
}
