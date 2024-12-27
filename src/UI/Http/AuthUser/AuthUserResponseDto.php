<?php

declare(strict_types=1);

namespace App\UI\Http\AuthUser;

final class AuthUserResponseDto
{
    public function __construct(
        public readonly string $accessToken,
        public readonly string $refreshToken,
    ) {
    }
}
