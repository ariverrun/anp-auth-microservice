<?php

declare(strict_types=1);

namespace App\UI\Http\AuthUser;

use Symfony\Component\Validator\Constraints as Assert;

final class AuthUserRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $username,
        #[Assert\NotBlank]
        public readonly string $password,
    ) {
    }
}
