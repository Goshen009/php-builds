<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;

class FilterMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : FilterMiddleware
    {
        return new FilterMiddleware();
    }
}
