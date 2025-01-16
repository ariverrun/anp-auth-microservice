<?php

declare(strict_types=1);

namespace App\Application\UseCase\AuthInGame;

interface AuthInGameUseCaseInterface
{
    public function __invoke(AuthInGameDto $dto): AuthInGameResult;
}
