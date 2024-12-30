<?php

declare(strict_types=1);

namespace App\UI\Http\CreateGameSession;

final class CreateGameSessionResponseDto
{
    public function __construct(
        public readonly int $gameSessionId,
    ) {
    }
}
