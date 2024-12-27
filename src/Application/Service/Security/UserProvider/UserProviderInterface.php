<?php

declare(strict_types=1);

namespace App\Application\Service\Security\UserProvider;

use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface as SymfonyUserProviderInterface;

/**
 * @extends SymfonyUserProviderInterface<UserInterface>
 */
interface UserProviderInterface extends SymfonyUserProviderInterface
{
    /**
     * @throws UserNotFoundException
     */
    public function getUserById(int $userId): UserInterface;
}
