<?php

declare(strict_types=1);

namespace Tests\Functional;

use App\Domain\Entity\ApiClientUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Crawler;

class ServiceApiClient
{
    use JsonRequestTrait;
    private const AUTH_TOKEN_HEADER_NAME = 'HTTP_X-Auth-Token';
    public function __construct(
        private readonly KernelBrowser $client,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function createApiClientUser(string $apiKey, array $roles): ApiClientUser
    {
        $apiClientUser = new ApiClientUser();
        $apiClientUser->setApiKey($apiKey);
        
        foreach ($roles as $role) {
            $apiClientUser->addRole($role);
        }

        $this->entityManager->persist($apiClientUser);
        $this->entityManager->flush();

        return $apiClientUser;
    }

    public function requestFromApiClientUser(ApiClientUser $apiClientUser, string $method, string $uri, array $parameters = [], array $server = [], array $jsonData = []): Crawler
    {
        $server[self::AUTH_TOKEN_HEADER_NAME] = $apiClientUser->getApiKey();
        return $this->jsonRequest($method, $uri, $parameters, $server, $jsonData);
    }

    public function requestAnonymously(string $method, string $uri, array $parameters = [], array $server = [], array $jsonData = []): Crawler
    {
        unset($server[self::AUTH_TOKEN_HEADER_NAME]);
        return $this->jsonRequest($method, $uri, $parameters, $server, $jsonData);
    }    
}