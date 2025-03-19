<?php

declare(strict_types=1);

namespace App\Middleware\SignUp;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

use function assert;

class SignUpUniqueFilterMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : SignUpUniqueFilterMiddleware
    {
        $entityManager = $container->get(EntityManagerInterface::class);
        assert($entityManager instanceof EntityManagerInterface);

        return new SignUpUniqueFilterMiddleware($entityManager);
    }
}
