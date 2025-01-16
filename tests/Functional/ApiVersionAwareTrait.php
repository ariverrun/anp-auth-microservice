<?php

declare(strict_types=1);

namespace Tests\Functional;

use Symfony\Component\DependencyInjection\Container;

/**
 * @param Container $container
 */
trait ApiVersionAwareTrait
{
    protected function getApiVersion() : string 
    {
        return $this->container->getParameter('api_version');
    }
}