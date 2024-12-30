<?php

declare(strict_types=1);

namespace App\Application\UseCase\AuthInGame;

final class AuthInGameResult
{
    public function __construct(
        public readonly string $accessToken,
    ) {
    }
}
