<?php

declare(strict_types=1);

namespace Tests\Functional;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

abstract class FunctionalTestCase extends WebTestCase
{
    protected readonly KernelBrowser $client;
    protected readonly Container $container;
    protected readonly EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->container = $this->getContainer();

        $this->entityManager = $this->container->get(EntityManagerInterface::class);
    }
}