<?php

declare(strict_types=1);

namespace App\Handler\Book;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class ReturnBookFactory
{
    public function __invoke(ContainerInterface $container) : ReturnBook
    {
        return new ReturnBook(
            $container->get(EntityManager::class)
        );
    }
}
