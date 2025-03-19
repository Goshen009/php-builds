<?php

declare(strict_types=1);

namespace App\Handler\User\Auth;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class SignupFactory
{
    public function __invoke(ContainerInterface $container) : Signup
    {
        return new Signup(
            $container->get(EntityManager::class)
        );
    }
}
