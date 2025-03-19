<?php

declare(strict_types=1);

namespace App\Handler\Book;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class BorrowBookFactory
{
    public function __invoke(ContainerInterface $container) : BorrowBook
    {
        return new BorrowBook(
            $container->get(EntityManager::class)
        );
    }
}
