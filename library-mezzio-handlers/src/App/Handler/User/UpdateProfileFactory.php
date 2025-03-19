<?php

declare(strict_types=1);

namespace App\Handler\User;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class UpdateProfileFactory
{
    public function __invoke(ContainerInterface $container) : UpdateProfile
    {
        return new UpdateProfile(
            $container->get(EntityManager::class)
        );
    }
}
