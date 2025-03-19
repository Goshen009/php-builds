<?php

declare(strict_types=1);

namespace App\Handler\User\Register;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class CompleteSignupFactory
{
    public function __invoke(ContainerInterface $container) : CompleteSignup
    {
        return new CompleteSignup(
            $container->get(EntityManager::class)
        );
    }
}
