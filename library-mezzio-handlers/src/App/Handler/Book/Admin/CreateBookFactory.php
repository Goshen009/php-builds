<?php

declare(strict_types=1);

namespace App\Handler\Book\Admin;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class CreateBookFactory
{
    public function __invoke(ContainerInterface $container) : CreateBook
    {
        return new CreateBook(
            $container->get(EntityManager::class)
        );
    }
}
