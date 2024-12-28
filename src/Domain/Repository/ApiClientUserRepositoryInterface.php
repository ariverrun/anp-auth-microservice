<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\ApiClientUser;

interface ApiClientUserRepositoryInterface
{
    public function findByApiKey(string $apiKey): ?ApiClientUser;
}
