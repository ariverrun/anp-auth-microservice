<?php

declare(strict_types=1);

namespace App\UI\Http\AuthInGame;

use Symfony\Component\Validator\Constraints as Assert;

final class AuthInGameRequestDto
{
    public function __construct(
        #[Assert\GreaterThan(0)]
        public readonly int $gameSessionId,
    ) {
    }
}
