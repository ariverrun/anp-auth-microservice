<?php

declare(strict_types=1);

namespace App\UI\Http\CreateGameSession;

use Symfony\Component\Validator\Constraints as Assert;

final class CreateGameSessionRequestDto
{
    /**
     * @param int[] $userIds
     */
    public function __construct(
        #[Assert\GreaterThan(0)]
        public readonly int $gameId,
        #[Assert\NotBlank]
        #[Assert\Unique]
        #[Assert\All([
            new Assert\Type('integer'),
            new Assert\GreaterThan(0),
        ])]
        public readonly array $userIds,
    ) {
    }
}
