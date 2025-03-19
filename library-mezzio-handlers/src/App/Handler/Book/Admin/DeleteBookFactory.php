<?php

declare(strict_types=1);

namespace App\Handler\Book\Admin;

use App\Services\CloudinaryService;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class DeleteBookFactory
{
    public function __invoke(ContainerInterface $container) : DeleteBook
    {
        return new DeleteBook(
            $container->get(EntityManager::class),
            $container->get(CloudinaryService::class)
        );
    }
}
