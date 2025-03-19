<?php

declare(strict_types=1);

namespace App\Handler\Book;

use Psr\Container\ContainerInterface;

class FindBookFactory
{
    public function __invoke(ContainerInterface $container) : FindBook
    {
        return new FindBook();
    }
}
