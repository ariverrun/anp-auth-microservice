<?php

declare(strict_types=1);

namespace App\Application\UseCase\AuthUser;

use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

interface AuthUserUseCaseInterface
{
    /**
     * @throws BadCredentialsException
     * @throws UserNotFoundException
     */
    public function __invoke(AuthUserDto $dto): AuthUserResult;
}
