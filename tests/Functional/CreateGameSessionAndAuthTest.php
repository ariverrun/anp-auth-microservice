<?php

declare(strict_types=1);

namespace Tests\Functional;

use App\Domain\Repository\GameSessionRepositoryInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class CreateGameSessionAndAuthTest extends FunctionalTestCase
{
    use ApiVersionAwareTrait;
    use JsonRequestTrait;
    private readonly GameSessionRepositoryInterface $gameSessionRepository;
    private readonly ServiceApiClient $serviceApiClient;
    protected function setUp(): void
    {
        parent::setUp();

        $this->gameSessionRepository = $this->container->get(GameSessionRepositoryInterface::class);
    
        $this->serviceApiClient = new ServiceApiClient($this->client, $this->entityManager);
    }

    public function testSuccessfulFlow(): void
    {    
        $apiClientUser = $this->serviceApiClient->createApiClientUser('12345678', ['ROLE_GAME_SERVICE']);

        $userId = 1;

        $gameId = 100;

        $this->serviceApiClient->requestFromApiClientUser($apiClientUser, 'POST', '/api/' . $this->getApiVersion() . '/game-session', [], [], [
            'gameId' => $gameId,
            'userIds' => [$userId, 2,3,4],
        ]);

        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();

        $responseContent = $response->getContent();
        $this->assertJson($responseContent);

        $responseData = json_decode($responseContent, true);
        $this->assertIsArray($responseData);

        $this->assertArrayHasKey('gameSessionId', $responseData);

        $gameSessionId = $responseData['gameSessionId'];

        $this->assertIsInt($gameSessionId);
        $this->assertGreaterThan(0, $gameSessionId);

        $gameSession = $this->gameSessionRepository->findById($gameSessionId);
        $this->assertNotNull($gameSession);

        $this->assertEquals($gameId, $gameSession->getGameId());
        $this->assertTrue($gameSession->containsUserId($userId));

        $userName = 'joe';
        $password = 'joe1234';

        $this->jsonRequest('POST', '/api/' . $this->getApiVersion() . '/user-auth', [], [], [
            'username' => $userName,
            'password' => $password,
        ]);

        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();

        $responseContent = $response->getContent();
        $this->assertJson($responseContent);

        $responseData = json_decode($responseContent, true);
        $this->assertIsArray($responseData);

        $this->assertArrayHasKey('token', $responseData);

        $accessToken = $responseData['token'];
        $this->assertGreaterThan(0, strlen($accessToken));

        $this->jsonRequest('POST', '/api/' . $this->getApiVersion() . '/game-auth', [], [
            'HTTP_Authorization' => 'Bearer ' . $accessToken,
        ], [
            'gameSessionId' => $gameSessionId,
        ]);      
        
        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();

        $responseContent = $response->getContent();
        $this->assertJson($responseContent);

        $responseData = json_decode($responseContent, true);
        $this->assertIsArray($responseData);

        $this->assertArrayHasKey('accessToken', $responseData);

        $gameAccessToken = $responseData['accessToken'];
        $this->assertGreaterThan(0, strlen($gameAccessToken));  
        
        /**  @var JWTTokenManagerInterface $jwtTokenManager */
        $jwtTokenManager = $this->container->get(JWTTokenManagerInterface::class);

        $payload = $jwtTokenManager->parse($gameAccessToken);

        $this->assertArrayHasKey('gameSessionId', $payload);
        $this->assertEquals($gameSessionId, $payload['gameSessionId']);        
    }

    public function testUserDoesntHaveAccessToGameSession(): void
    {    
        $apiClientUser = $this->serviceApiClient->createApiClientUser('5555555', ['ROLE_GAME_SERVICE']);

        $userId = 1;

        $gameId = 100;

        $this->serviceApiClient->requestFromApiClientUser($apiClientUser, 'POST', '/api/' . $this->getApiVersion() . '/game-session', [], [], [
            'gameId' => $gameId,
            'userIds' => [2,3,4],
        ]);

        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();

        $responseContent = $response->getContent();
        $this->assertJson($responseContent);

        $responseData = json_decode($responseContent, true);
        $this->assertIsArray($responseData);

        $this->assertArrayHasKey('gameSessionId', $responseData);

        $gameSessionId = $responseData['gameSessionId'];

        $this->assertIsInt($gameSessionId);
        $this->assertGreaterThan(0, $gameSessionId);

        $gameSession = $this->gameSessionRepository->findById($gameSessionId);
        $this->assertNotNull($gameSession);

        $this->assertEquals($gameId, $gameSession->getGameId());
        $this->assertFalse($gameSession->containsUserId($userId));

        $userName = 'joe';
        $password = 'joe1234';

        $this->jsonRequest('POST', '/api/' . $this->getApiVersion() . '/user-auth', [], [], [
            'username' => $userName,
            'password' => $password,
        ]);

        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();

        $responseContent = $response->getContent();
        $this->assertJson($responseContent);

        $responseData = json_decode($responseContent, true);
        $this->assertIsArray($responseData);

        $this->assertArrayHasKey('token', $responseData);

        $accessToken = $responseData['token'];
        $this->assertGreaterThan(0, strlen($accessToken));

        $this->jsonRequest('POST', '/api/' . $this->getApiVersion() . '/game-auth', [], [
            'HTTP_Authorization' => 'Bearer ' . $accessToken,
        ], [
            'gameSessionId' => $gameSessionId,
        ]);      
        
        $response = $this->client->getResponse();
        $this->assertResponseStatusCodeSame(500);    
    }

    public function testUnauthorizedGameSessionCreation(): void
    {
        $userId = 1;

        $gameId = 100;

        $this->serviceApiClient->requestAnonymously('POST', '/api/' . $this->getApiVersion() . '/game-session', [], [], [
            'gameId' => $gameId,
            'userIds' => [$userId, 2,3,4],
        ]);

        $this->assertResponseStatusCodeSame(401);
    }

    public function testGameSessionCreationWithoutRoleForIt(): void
    {    
        $apiClientUser = $this->serviceApiClient->createApiClientUser('23455678', ['ROLE_SOME_SERVICE']);

        $userId = 1;

        $gameId = 100;

        $this->serviceApiClient->requestFromApiClientUser($apiClientUser, 'POST', '/api/' . $this->getApiVersion() . '/game-session', [], [], [
            'gameId' => $gameId,
            'userIds' => [$userId, 2,3,4],
        ]);

        $this->assertResponseStatusCodeSame(403);
    }    
}