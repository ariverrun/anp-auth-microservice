<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Repository\GameSessionRepositoryInterface;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity(repositoryClass: GameSessionRepositoryInterface::class)]
#[ORM\Table(name: 'game_session')]
class GameSession
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    /* @phpstan-ignore-next-line */
    private ?int $id = null;

    /**
     * @param int[] $userIds
     */
    public function __construct(
        #[ORM\Column(type: 'integer')]
        private int $gameId,
        #[ORM\Column(type: 'json')]
        private array $userIds,
    ) {
        Assert::greaterThan($gameId, 0);
        /* @phpstan-ignore-next-line */
        Assert::allInteger($userIds);
        Assert::allGreaterThan($userIds, 0);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGameId(): int
    {
        return $this->gameId;
    }

    public function containsUserId(int $userId): bool
    {
        return in_array($userId, $this->userIds);
    }
}
