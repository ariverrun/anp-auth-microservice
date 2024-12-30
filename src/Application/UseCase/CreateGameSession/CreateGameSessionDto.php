<?php

declare(strict_types=1);

namespace App\Application\UseCase\CreateGameSession;

final class CreateGameSessionDto
{
    /**
     * @param int[] $userIds
     */
    public function __construct(
        public readonly int $gameId,
        public readonly array $userIds,
    ) {
    }
}
