<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;

class ValidateAddBookMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : ValidateAddBookMiddleware
    {
        return new ValidateAddBookMiddleware();
    }
}
