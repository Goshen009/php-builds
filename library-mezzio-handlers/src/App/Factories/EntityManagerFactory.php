<?php
declare(strict_types=1);

namespace App\Factories;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Psr\Container\ContainerInterface;

class EntityManagerFactory {
    public function __invoke(ContainerInterface $container): EntityManager {
        $config = $container->get('config')['doctrine'];

        $ormConfig = ORMSetup::createAttributeMetadataConfiguration(
            paths: $config['driver']['my_entities']['paths'],
            isDevMode: true
        );

        \Doctrine\DBAL\Types\Type::addType('uuid', 'Ramsey\Uuid\Doctrine\UuidType');

        $dbParams = $config['connection']['orm_default']['params'];
        $connection = DriverManager::getConnection([
            'dbname'   => $dbParams['dbname'],
            'user'     => $dbParams['user'],
            'password' => $dbParams['password'],
            'host'     => $dbParams['host'],
            'driver'   => $dbParams['driver'],
        ], $ormConfig);

        return new EntityManager($connection, $ormConfig);
    }
}
