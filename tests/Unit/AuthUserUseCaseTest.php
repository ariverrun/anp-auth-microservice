<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Application\UseCase\AuthUser\AuthUserDto;
use App\Application\UseCase\AuthUser\AuthUserUseCase;
use Doctrine\ORM\EntityManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use PHPUnit\Framework\TestCase;

final class AuthUserUseCaseTest extends TestCase
{
    public function testSuccessfulAuth(): void
    {
        $userMock = $this->createMock(UserInterface::class);

        $userProviderMock = $this->createMock(UserProviderInterface::class);
        $userProviderMock->expects($this->once())
                                ->method('loadUserByIdentifier')
                                ->willReturn($userMock);

        $accessToken = 'access-token-string';

        $jwtTokenManagerMock = $this->createMock(JWTTokenManagerInterface::class);
        $jwtTokenManagerMock->expects($this->once())
                                ->method('create')
                                ->willReturn($accessToken);
        
        $refreshTokenTtl = 10000;

        $refreshTokenGeneratorMock = $this->createMock(RefreshTokenGeneratorInterface::class);
        $refreshTokenGeneratorMock->expects($this->once())
                                ->method('createForUserWithTtl')
                                ->willReturnCallback(function(UserInterface $user, int $ttl): RefreshTokenInterface {
                                    $refreshTokenMock = $this->createMock(RefreshTokenInterface::class);
                                    $refreshTokenMock->expects($this->once())
                                                    ->method('getRefreshToken')
                                                    ->willReturn((string)$ttl);
                                    return $refreshTokenMock;
                                });
                        
                                
        $authUserUseCase = new AuthUserUseCase(
            $userProviderMock,
            $this->createMock(UserPasswordHasherInterface::class),
            $jwtTokenManagerMock,
            $refreshTokenGeneratorMock,
            $refreshTokenTtl,
            $this->createMock(EntityManagerInterface::class),            
        );

        $dto = new AuthUserDto('username', 'password');

        $result = ($authUserUseCase)($dto);

        $this->assertEquals($result->accessToken, $accessToken);
        $this->assertEquals($result->refreshToken, (string)$refreshTokenTtl);
    }    
    public function testInvalidPassword(): void
    {
        $userMock = $this->createMockForIntersectionOfInterfaces([PasswordAuthenticatedUserInterface::class, UserInterface::class]);

        $userProviderMock = $this->createMock(UserProviderInterface::class);
        $userProviderMock->expects($this->once())
                                ->method('loadUserByIdentifier')
                                ->willReturn($userMock);

        $userPasswordHasherMock = $this->createMock(UserPasswordHasherInterface::class);
        $userPasswordHasherMock->expects($this->once())
                                ->method('isPasswordValid')
                                ->willReturn(false);

        $authUserUseCase = new AuthUserUseCase(
            $userProviderMock,
            $userPasswordHasherMock,
            $this->createMock(JWTTokenManagerInterface::class),
            $this->createMock(RefreshTokenGeneratorInterface::class),
            3600,
            $this->createMock(EntityManagerInterface::class),            
        );

        $dto = new AuthUserDto('username', 'password');

        $this->expectException(BadCredentialsException::class);

        ($authUserUseCase)($dto);
    }
}