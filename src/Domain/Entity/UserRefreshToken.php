<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken as BaseRefreshToken;

#[ORM\Entity]
#[ORM\Table(name: 'user_refresh_token')]
class UserRefreshToken extends BaseRefreshToken
{
}
