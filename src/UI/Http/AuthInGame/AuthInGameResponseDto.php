<?php

declare(strict_types=1);

namespace App\UI\Http\AuthInGame;

final class AuthInGameResponseDto
{
    public function __construct(
        public readonly string $accessToken,
    ) {
    }
}
