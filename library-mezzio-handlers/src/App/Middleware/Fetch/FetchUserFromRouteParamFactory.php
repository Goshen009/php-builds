<?php

declare(strict_types=1);

namespace App\Middleware\Fetch;

use Doctrine\ORM\EntityManager;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

class FetchUserFromRouteParamFactory
{
    public function __invoke(ContainerInterface $container) : FetchUserFromRouteParam
    {
        return new FetchUserFromRouteParam(
            $container->get(ProblemDetailsResponseFactory::class),
            $container->get(EntityManager::class)
        );
    }
}
