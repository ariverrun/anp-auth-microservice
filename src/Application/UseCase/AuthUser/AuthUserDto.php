<?php

declare(strict_types=1);

namespace App\Application\UseCase\AuthUser;

final class AuthUserDto
{
    public function __construct(
        public readonly string $username,
        public readonly string $password,
    ) {
    }
}
