<?php

declare(strict_types=1);

namespace App\Middleware\Validate;

use Doctrine\ORM\EntityManager;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

class ValidRoleFactory
{
    public function __invoke(ContainerInterface $container) : ValidRole
    {
        return new ValidRole(
            $container->get(ProblemDetailsResponseFactory::class),
            $container->get(EntityManager::class)
        );
    }
}
