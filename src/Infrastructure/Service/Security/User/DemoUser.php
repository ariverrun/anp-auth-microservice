<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class DemoUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    private string $hashedPassword;

    /**
     * @param string[] $roles
     */
    public function __construct(
        private int $id,
        private string $username,
        /* @phpstan-ignore-next-line */
        private string $plainPassword,
        private array $roles,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {

    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function setHashedPassword(string $hashedPassord): self
    {
        $this->hashedPassword = $hashedPassord;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->hashedPassword;
    }
}
