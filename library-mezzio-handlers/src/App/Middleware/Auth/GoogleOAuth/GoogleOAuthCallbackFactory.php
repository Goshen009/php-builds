<?php

declare(strict_types=1);

namespace App\Middleware\Auth\GoogleOAuth;

use App\Services\GoogleOAuthService;
use Doctrine\ORM\EntityManager;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

class GoogleOAuthCallbackFactory
{
    public function __invoke(ContainerInterface $container) : GoogleOAuthCallback
    {
        return new GoogleOAuthCallback(
            $container->get(ProblemDetailsResponseFactory::class),
            $container->get(GoogleOAuthService::class),
            $container->get(EntityManager::class)
        );
    }
}
