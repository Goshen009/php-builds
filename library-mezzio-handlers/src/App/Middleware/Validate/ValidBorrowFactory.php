<?php

declare(strict_types=1);

namespace App\Middleware\Validate;

use Doctrine\ORM\EntityManager;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

class ValidBorrowFactory
{
    public function __invoke(ContainerInterface $container) : ValidBorrow
    {
        return new ValidBorrow(
            $container->get(ProblemDetailsResponseFactory::class),
            $container->get(EntityManager::class)
        );
    }
}
