<?php

declare(strict_types=1);

namespace App\Middleware\Auth;

use App\Services\JWT;
use Doctrine\ORM\EntityManager;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

class ValidateJWTFactory
{
    public function __invoke(ContainerInterface $container) : ValidateJWT
    {
        return new ValidateJWT(
            $container->get(ProblemDetailsResponseFactory::class),
            $container->get(EntityManager::class),
            $container->get(JWT::class)
        );
    }
}
