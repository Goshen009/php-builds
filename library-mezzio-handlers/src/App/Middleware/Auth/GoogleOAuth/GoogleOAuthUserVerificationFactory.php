<?php

declare(strict_types=1);

namespace App\Middleware\Auth\GoogleOAuth;

use Doctrine\ORM\EntityManager;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

class GoogleOAuthUserVerificationFactory
{
    public function __invoke(ContainerInterface $container) : GoogleOAuthUserVerification
    {
        return new GoogleOAuthUserVerification(
            $container->get(ProblemDetailsResponseFactory::class),
            $container->get(EntityManager::class)
        );
    }
}
