<?php

declare(strict_types=1);

namespace App\Handler\Book;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class FindAllBooksFactory
{
    public function __invoke(ContainerInterface $container) : FindAllBooks
    {
        return new FindAllBooks(
            $container->get(EntityManager::class)
        );
    }
}
