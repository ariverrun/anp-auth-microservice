<?php

declare(strict_types=1);

namespace App\UI\Http\AuthUser;

use App\Application\UseCase\AuthUser\AuthUserDto;
use App\Application\UseCase\AuthUser\AuthUserUseCaseInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class AuthUserController extends AbstractController
{
    public function __construct(
        private readonly AuthUserUseCaseInterface $authUserUseCase,
    ) {
    }

    #[OA\Response(
        response: 200,
        description: 'Returns user JWT',
        content: new OA\JsonContent(ref: new Model(type: AuthUserResponseDto::class)),
    )]
    #[Route(path: '/user-auth', name: 'app_api_user_auth', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload]
        AuthUserRequestDto $requestDto,
    ): JsonResponse {
        $result = ($this->authUserUseCase)(new AuthUserDto(
            username: $requestDto->username,
            password: $requestDto->password,
        ));

        return $this->json(
            new AuthUserResponseDto(
                accessToken: $result->accessToken,
                refreshToken: $result->refreshToken
            )
        );
    }
}
