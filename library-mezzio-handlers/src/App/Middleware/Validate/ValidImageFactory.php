<?php

declare(strict_types=1);

namespace App\Middleware\Validate;

use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

class ValidImageFactory
{
    public function __invoke(ContainerInterface $container) : ValidImage
    {
        return new ValidImage(
            $container->get(ProblemDetailsResponseFactory::class),
        );
    }
}
