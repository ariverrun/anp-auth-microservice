<?php

declare(strict_types=1);

namespace App\Application\UseCase\AuthInGame;

final class AuthInGameDto
{
    public function __construct(
        public readonly int $userId,
        public readonly int $gameSessionId,
    ) {
    }
}
