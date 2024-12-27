<?php

declare(strict_types=1);

namespace App\Application\UseCase\AuthUser;

final class AuthUserResult
{
    public function __construct(
        public readonly string $accessToken,
        public readonly string $refreshToken,
    ) {
    }
}
