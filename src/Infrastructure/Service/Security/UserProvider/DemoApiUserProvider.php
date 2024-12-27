<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Security\UserProvider;

use App\Application\Service\Security\UserProvider\UserProviderInterface;
use App\Infrastructure\Service\Security\User\DemoUser;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DemoApiUserProvider implements UserProviderInterface
{
    /**
     * @var array<string,DemoUser>
     */
    private readonly array $users;
    /**
     * @param array<int,array{id: int, username: string, password: string, roles: string[]}> $demoUsersData
     */
    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        array $demoUsersData,
    ) {
        $users = [];

        foreach ($demoUsersData as $userData) {
            $userId = (int)$userData['id'];
            $username = $userData['username'];

            $user = new DemoUser(
                $userId,
                $username,
                $userData['password'],
                $userData['roles'],
            );

            $hashedPassword = $passwordHasher->hashPassword($user, $userData['password']);

            $user->setHashedPassword($hashedPassword);

            $users[$username] = $user;
        }

        $this->users = $users;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return DemoUser::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        if (!isset($this->users[$identifier])) {
            throw new UserNotFoundException();
        }

        return $this->users[$identifier];
    }

    public function getUserById(int $userId): UserInterface
    {
        foreach ($this->users as $user) {
            if ($userId === $user->getId()) {
                return $user;
            }
        }

        throw new UserNotFoundException();
    }
}
