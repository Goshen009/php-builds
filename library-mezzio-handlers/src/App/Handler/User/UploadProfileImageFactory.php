<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\Services\CloudinaryService;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class UploadProfileImageFactory
{
    public function __invoke(ContainerInterface $container) : UploadProfileImage
    {
        return new UploadProfileImage(
            $container->get(EntityManager::class),
            $container->get(CloudinaryService::class)
        );
    }
}
