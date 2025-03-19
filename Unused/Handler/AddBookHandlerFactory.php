<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;

class AddBookHandlerFactory
{
    public function __invoke(ContainerInterface $container) : AddBookHandler
    {
        return new AddBookHandler();
    }
}
