<?php

declare(strict_types=1);

namespace App\Middleware\Fetch;

use Doctrine\ORM\EntityManager;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

class FetchBookFactory
{
    public function __invoke(ContainerInterface $container) : FetchBook
    {
        return new FetchBook(
            $container->get(ProblemDetailsResponseFactory::class),
            $container->get(EntityManager::class)
        );
    }
}
