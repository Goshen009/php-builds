<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;

class ValidateSignUpMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : ValidateSignUpMiddleware
    {
        return new ValidateSignUpMiddleware();
    }
}
