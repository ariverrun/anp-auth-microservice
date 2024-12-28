<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\GameSession;

interface GameSessionRepositoryInterface
{
    public function findById(int $gameSessionId): ?GameSession;
}
