<?php

declare(strict_types=1);

namespace App\Handler\User\Admin;

use App\Services\CloudinaryService;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class DeleteUserFactory
{
    public function __invoke(ContainerInterface $container) : DeleteUser
    {
        return new DeleteUser(
            $container->get(EntityManager::class),
            $container->get(CloudinaryService::class)
        );
    }
}
