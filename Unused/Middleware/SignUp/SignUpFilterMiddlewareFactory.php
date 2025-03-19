<?php

declare(strict_types=1);

namespace App\Middleware\SignUp;

use Psr\Container\ContainerInterface;

class SignUpFilterMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : SignUpFilterMiddleware
    {
        return new SignUpFilterMiddleware();
    }
}
