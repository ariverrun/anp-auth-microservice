<?php

declare(strict_types=1);

namespace App\UI\Http\AuthInGame;

use App\Application\UseCase\AuthInGame\AuthInGameDto;
use App\Application\UseCase\AuthInGame\AuthInGameUseCaseInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class AuthInGameController extends AbstractController
{
    public function __construct(
        private readonly AuthInGameUseCaseInterface $authInGameUseCase,
    ) {
    }

    #[OA\Response(
        response: 200,
        description: 'Returns JWT to access desired game',
        content: new OA\JsonContent(ref: new Model(type: AuthInGameResponseDto::class)),
    )]
    #[Security(name: 'UserJWTBearer')]
    #[Route(path: '/game-auth', name: 'app_api_game_auth', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload]
        AuthInGameRequestDto $requestDto,
    ): JsonResponse {
        $result = ($this->authInGameUseCase)(new AuthInGameDto(
            userId: 1,
            gameSessionId: $requestDto->gameSessionId,
        ));

        return $this->json(
            new AuthInGameResponseDto($result->accessToken)
        );
    }
}
