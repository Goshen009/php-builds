<?php

declare(strict_types=1);

namespace App\Middleware\Auth\GoogleOAuth;

use Doctrine\ORM\EntityManager;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

class RegisterWithGoogleFactory
{
    public function __invoke(ContainerInterface $container) : RegisterWithGoogle
    {
        return new RegisterWithGoogle(
            $container->get(ProblemDetailsResponseFactory::class),
            $container->get(EntityManager::class)
        );
    }
}
