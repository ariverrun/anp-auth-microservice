<?php

declare(strict_types=1);

namespace App\Application\UseCase\AuthInGame\Exception;

use App\Application\Exception\ApplicationRuntimeException;

class UserDoesntHaveAccessToGameSessionException extends ApplicationRuntimeException
{
    public function __construct(
        string $message = 'Access Denied',
    ) {
        parent::__construct($message, 403);
    }
}
