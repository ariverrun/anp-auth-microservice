<?php

declare(strict_types=1);

namespace Tests\Functional;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @param KernelBrowser $client
 */
trait JsonRequestTrait
{
    public function jsonRequest(string $method, string $uri, array $parameters = [], array $server = [], array $jsonData = []): Crawler 
    {
        $content = json_encode($jsonData, JSON_THROW_ON_ERROR);

        $server = array_merge([
            'CONTENT_TYPE' => 'application/json',
        ], $server);
        
        return $this->client->request($method, $uri, $parameters, [], $server, $content);
    }
}