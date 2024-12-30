<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\GameSession;
use App\Domain\Repository\GameSessionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GameSession>
 */
class GameSessionRepository extends ServiceEntityRepository implements GameSessionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameSession::class);
    }
    public function findById(int $gameSessionId): ?GameSession
    {
        return $this->find($gameSessionId);
    }
}
