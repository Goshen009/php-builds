<?php

declare(strict_types=1);

namespace App\Middleware\Validate;

use Doctrine\ORM\EntityManager;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

class VerifyUpdateProfileDetailsFactory
{
    public function __invoke(ContainerInterface $container) : VerifyUpdateProfileDetails
    {
        return new VerifyUpdateProfileDetails(
            $container->get(ProblemDetailsResponseFactory::class),
            $container->get(EntityManager::class)
        );
    }
}
