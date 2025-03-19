<?php

declare(strict_types=1);

namespace App\Handler\Book\Admin;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class EditBookFactory
{
    public function __invoke(ContainerInterface $container) : EditBook
    {
        return new EditBook(
            $container->get(EntityManager::class)
        );
    }
}
