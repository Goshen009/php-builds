<?php

declare(strict_types=1);

namespace App\Handler\User\Admin;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class ChangeUserRoleFactory
{
    public function __invoke(ContainerInterface $container) : ChangeUserRole
    {
        return new ChangeUserRole(
            $container->get(EntityManager::class)
        );
    }
}
