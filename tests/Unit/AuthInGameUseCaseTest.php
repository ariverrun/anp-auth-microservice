<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Application\UseCase\AuthInGame\AuthInGameDto;
use App\Application\UseCase\AuthInGame\AuthInGameUseCase;
use App\Application\UseCase\AuthInGame\Exception\UserDoesntHaveAccessToGameSessionException;
use App\Application\Service\Security\UserProvider\UserProviderInterface;
use App\Domain\Entity\GameSession;
use App\Domain\Repository\GameSessionRepositoryInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use PHPUnit\Framework\TestCase;

final class AuthInGameUseCaseTest extends TestCase
{
    public function testSuccessfulAuth(): void
    {
        $userMock = $this->createMock(UserInterface::class);

        $userProviderMock = $this->createMock(UserProviderInterface::class);
        $userProviderMock->expects($this->once())
                                ->method('getUserById')
                                ->willReturn($userMock);        

        $accessToken = 'access-token-string';

        $jwtTokenManagerMock = $this->createMock(JWTTokenManagerInterface::class);
        $jwtTokenManagerMock->expects($this->once())
                                ->method('createFromPayload')
                                ->willReturn($accessToken);         

        $gameSessionMock = $this->createMock(GameSession::class);
        $gameSessionMock->expects($this->once())
                                ->method('containsUserId')
                                ->willReturn(true);      

        $gameSessionRepositoryMock = $this->createMock(GameSessionRepositoryInterface::class);
        $gameSessionRepositoryMock->expects($this->once())
                                ->method('findById')
                                ->willReturn($gameSessionMock);
                                
        $result = (new AuthInGameUseCase(
            $userProviderMock,
            $jwtTokenManagerMock,
            $gameSessionRepositoryMock,
        ))(
            new AuthInGameDto(
                1,
                2,
            ),
        );

        $this->assertEquals($result->accessToken, $accessToken);
    }

    public function testDoesntHaveAccess(): void
    {
        $userMock = $this->createMock(UserInterface::class);

        $userProviderMock = $this->createMock(UserProviderInterface::class);
        $userProviderMock->expects($this->once())
                                ->method('getUserById')
                                ->willReturn($userMock);        

        $accessToken = 'access-token-string';

        $jwtTokenManagerMock = $this->createMock(JWTTokenManagerInterface::class);
        $jwtTokenManagerMock->expects($this->never())
                                ->method('createFromPayload')
                                ->willReturn($accessToken);         

        $gameSessionMock = $this->createMock(GameSession::class);
        $gameSessionMock->expects($this->once())
                                ->method('containsUserId')
                                ->willReturn(false);

        $gameSessionRepositoryMock = $this->createMock(GameSessionRepositoryInterface::class);
        $gameSessionRepositoryMock->expects($this->once())
                                ->method('findById')
                                ->willReturn($gameSessionMock);
                               
        $this->expectException(
            UserDoesntHaveAccessToGameSessionException::class
        );
                                
        (new AuthInGameUseCase(
            $userProviderMock,
            $jwtTokenManagerMock,
            $gameSessionRepositoryMock,
        ))(
            new AuthInGameDto(
                1,
                2,
            ),
        );
    }    
}