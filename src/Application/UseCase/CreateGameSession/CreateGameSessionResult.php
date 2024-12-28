<?php

declare(strict_types=1);

namespace App\Application\UseCase\CreateGameSession;

final class CreateGameSessionResult
{
    public function __construct(
        public readonly int $gameSessionId,
    ) {
    }
}
