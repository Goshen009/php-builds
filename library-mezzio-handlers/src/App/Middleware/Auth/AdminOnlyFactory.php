<?php

declare(strict_types=1);

namespace App\Middleware\Auth;

use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

class AdminOnlyFactory
{
    public function __invoke(ContainerInterface $container) : AdminOnly
    {
        return new AdminOnly(
            $container->get(ProblemDetailsResponseFactory::class)
        );
    }
}
