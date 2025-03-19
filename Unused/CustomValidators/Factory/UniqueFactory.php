<?php

declare(strict_types=1);

namespace App\CustomValidators\Factory;

use App\CustomValidators\Unique;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

final class UniqueFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $entityManager = $container->get(EntityManagerInterface::class);
        assert($entityManager instanceof EntityManagerInterface);

        if (!isset($options['class'], $options['column'])) {
            throw new \InvalidArgumentException('Both "class" and "column" options are required');
        }

        return new Unique($entityManager, $options);
    }
}
