<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Repository\ApiClientUserRepositoryInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: ApiClientUserRepositoryInterface::class)]
#[ORM\Table(name: '`api_client_user`')]
#[ORM\UniqueConstraint(name: 'apiKey', fields: ['apiKey'])]
class ApiClientUser implements UserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    /* @phpstan-ignore-next-line */
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    /* @phpstan-ignore-next-line */
    private string $apiKey;

    #[ORM\Column(type: 'json')]
    /**
     * @param string[] $roles
     */
    /* @phpstan-ignore-next-line */
    private array $roles = [];

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->apiKey;
    }
}
