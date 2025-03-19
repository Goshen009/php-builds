<?php

declare(strict_types=1);

namespace App\Middleware\Validate;

use App\Services\JWT;
use Doctrine\ORM\EntityManager;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

class VerifyLoginDetailsFactory
{
    public function __invoke(ContainerInterface $container) : VerifyLoginDetails
    {
        return new VerifyLoginDetails(
            $container->get(ProblemDetailsResponseFactory::class),
            $container->get(EntityManager::class),
            $container->get(JWT::class)
        );
    }
}
