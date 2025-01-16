<?php

declare(strict_types=1);

namespace App\Application\UseCase\CreateGameSession;

interface CreateGameSessionUseCaseInterface
{
    public function __invoke(CreateGameSessionDto $dto): CreateGameSessionResult;
}
