<?php

declare(strict_types=1);

namespace Tests\Mock;

use Gesdinet\JWTRefreshTokenBundle\Doctrine\RefreshTokenRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class RefreshTokenRepositoryMock extends EntityRepository implements RefreshTokenRepositoryInterface
{
    public function __construct() 
    {
    }    

    public function findInvalid($datetime = null)
    {
        return [];
    }

    public function findOneBy(array $criteria, array|null $orderBy = null): object|null
    {
        return null;
    }
}