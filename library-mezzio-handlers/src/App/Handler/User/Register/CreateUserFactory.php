<?php

declare(strict_types=1);

namespace App\Handler\User\Register;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class CreateUserFactory
{
    public function __invoke(ContainerInterface $container) : CreateUser
    {
        return new CreateUser(
            $container->get(EntityManager::class)
        );
    }
}
