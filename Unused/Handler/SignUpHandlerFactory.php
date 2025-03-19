<?php

declare(strict_types=1);

namespace App\Handler;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

use function assert;

class SignUpHandlerFactory
{
    public function __invoke(ContainerInterface $container) : SignUpHandler
    {
        $entityManager = $container->get(EntityManagerInterface::class);
        assert($entityManager instanceof EntityManagerInterface);
        
        return new SignUpHandler($entityManager);
    }
}
