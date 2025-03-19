<?php

declare(strict_types=1);

namespace App\Handler\Book\Admin;

use App\Services\CloudinaryService;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class UploadBookImageFactory
{
    public function __invoke(ContainerInterface $container) : UploadBookImage
    {
        return new UploadBookImage(
            $container->get(EntityManager::class),
            $container->get(CloudinaryService::class)
        );
    }
}
