<?php

declare(strict_types=1);

namespace App\Middleware\Validate;

use Doctrine\ORM\EntityManager;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

class VerifyEditBookDetailsFactory
{
    public function __invoke(ContainerInterface $container) : VerifyEditBookDetails
    {
        return new VerifyEditBookDetails(
            $container->get(ProblemDetailsResponseFactory::class),
            $container->get(EntityManager::class)
        );
    }
}
