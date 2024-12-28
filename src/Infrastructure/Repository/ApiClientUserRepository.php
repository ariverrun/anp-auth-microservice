<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\ApiClientUser;
use App\Domain\Repository\ApiClientUserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ApiClientUser>
 */
class ApiClientUserRepository extends ServiceEntityRepository implements ApiClientUserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiClientUser::class);
    }
    public function findByApiKey(string $apiKey): ?ApiClientUser
    {
        return $this->findOneBy([
            'apiKey' => $apiKey,
        ]);
    }
}
