<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Application\Service\Security\UserProvider\UserProviderInterface;
use App\Application\UseCase\AuthUser\AuthUserDto;
use App\Application\UseCase\AuthUser\AuthUserUseCase;
use App\Domain\Entity\UserRefreshToken;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthUserUseCaseTest extends KernelTestCase
{
    public function testSuccessfulAuth(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $userIdentifier = 'user';
        $userPassword = 'user1234';

        $userMock = $this->createMockForIntersectionOfInterfaces([PasswordAuthenticatedUserInterface::class, UserInterface::class]);

        $userPasswordHasher = $container->get(UserPasswordHasherInterface::class);

        $hashedPassword = $userPasswordHasher->hashPassword($userMock, $userPassword);

        $userMock->expects($this->any())
                    ->method('getPassword')
                    ->willReturn($hashedPassword);

        $userMock->expects($this->any())
                    ->method('getUserIdentifier')
                    ->willReturn($userIdentifier);
                    
        $userProviderMock = $this->createMock(UserProviderInterface::class);
        $userProviderMock->expects($this->once())
                                ->method('loadUserByIdentifier')
                                ->willReturn($userMock);        

        $container->set(UserProviderInterface::class, $userProviderMock);

        $refreshTokenManagerMock = $this->createMock(RefreshTokenManagerInterface::class);
        $refreshTokenManagerMock->expects($this->any())
                                ->method('getClass')
                                ->willReturn(UserRefreshToken::class);

        $container->set(RefreshTokenManagerInterface::class, $refreshTokenManagerMock);

        $authUserUseCase = $container->get(AuthUserUseCase::class);
        $dto = new AuthUserDto($userIdentifier, $userPassword);

        $result = ($authUserUseCase)($dto);

        $this->assertGreaterThan(0, strlen($result->accessToken));
        $this->assertGreaterThan(0, strlen($result->refreshToken));
    }

    public function testInvalidPassword(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $userIdentifier = 'user';
        $userPassword = 'user1234';
        $userInvalidPassword = 'user5678';

        $userMock = $this->createMockForIntersectionOfInterfaces([PasswordAuthenticatedUserInterface::class, UserInterface::class]);

        $userPasswordHasher = $container->get(UserPasswordHasherInterface::class);

        $hashedPassword = $userPasswordHasher->hashPassword($userMock, $userPassword);

        $userMock->expects($this->any())
                    ->method('getPassword')
                    ->willReturn($hashedPassword);

        $userMock->expects($this->any())
                    ->method('getUserIdentifier')
                    ->willReturn($userIdentifier);
                    
        $userProviderMock = $this->createMock(UserProviderInterface::class);
        $userProviderMock->expects($this->once())
                                ->method('loadUserByIdentifier')
                                ->willReturn($userMock);        

        $container->set(UserProviderInterface::class, $userProviderMock);

        $refreshTokenManagerMock = $this->createMock(RefreshTokenManagerInterface::class);
        $refreshTokenManagerMock->expects($this->any())
                                ->method('getClass')
                                ->willReturn(UserRefreshToken::class);

        $container->set(RefreshTokenManagerInterface::class, $refreshTokenManagerMock);

        $authUserUseCase = $container->get(AuthUserUseCase::class);
        $dto = new AuthUserDto($userIdentifier, $userInvalidPassword);

        $this->expectException(BadCredentialsException::class);

        ($authUserUseCase)($dto);
    }    
}