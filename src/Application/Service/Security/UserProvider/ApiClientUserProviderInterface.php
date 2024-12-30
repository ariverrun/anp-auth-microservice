<?php

declare(strict_types=1);

namespace App\Application\Service\Security\UserProvider;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface as SymfonyUserProviderInterface;

/**
 * @extends SymfonyUserProviderInterface<UserInterface>
 */
interface ApiClientUserProviderInterface extends SymfonyUserProviderInterface
{
}
