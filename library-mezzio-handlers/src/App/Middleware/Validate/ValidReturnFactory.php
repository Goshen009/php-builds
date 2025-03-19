<?php

declare(strict_types=1);

namespace App\Middleware\Validate;

use Doctrine\ORM\EntityManager;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

class ValidReturnFactory
{
    public function __invoke(ContainerInterface $container) : ValidReturn
    {
        return new ValidReturn(
            $container->get(ProblemDetailsResponseFactory::class),
            $container->get(EntityManager::class)
        );
    }
}
