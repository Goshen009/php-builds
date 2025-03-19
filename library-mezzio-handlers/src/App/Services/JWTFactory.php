<?php

declare(strict_types=1);

namespace App\Services;

use Psr\Container\ContainerInterface;

class JWTFactory
{
    public function __invoke(ContainerInterface $container) : JWT
    {
        return new JWT(
            $container->get('config')['jwt']
        );
    }
}
