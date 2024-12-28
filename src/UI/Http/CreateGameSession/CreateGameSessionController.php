<?php

declare(strict_types=1);

namespace App\UI\Http\CreateGameSession;

use App\Application\UseCase\CreateGameSession\CreateGameSessionDto;
use App\Application\UseCase\CreateGameSession\CreateGameSessionUseCaseInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateGameSessionController extends AbstractController
{
    public function __construct(
        private readonly CreateGameSessionUseCaseInterface $createGameSessionUseCase,
    ) {
    }
    #[OA\Response(
        response: 200,
        description: 'Creates session for a game, registers users in it',
        content: new OA\JsonContent(ref: new Model(type: CreateGameSessionResponseDto::class)),
    )]
    #[Security(name: 'GameServiceApiToken')]
    #[Route(path: '/game-session', name: 'app_api_create_game_sesion', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload]
        CreateGameSessionRequestDto $requestDto,
    ): JsonResponse {
        return $this->json(
            new CreateGameSessionResponseDto(
                ($this->createGameSessionUseCase)(
                    new CreateGameSessionDto(
                        gameId: $requestDto->gameId,
                        userIds: $requestDto->userIds,
                    ),
                )->gameSessionId,
            ),
        );
    }
}
