<?php
declare(strict_types=1);

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'params' => [
                    'host'     => $_ENV['DB_HOST'],
                    'dbname'   => $_ENV['DB_NAME'],
                    'user'     => $_ENV['DB_USER'],
                    'password' => $_ENV['DB_PASSWORD'],
                    'driver'   => $_ENV['DB_DRIVER'],
                ],
                'types' => [
                    \Ramsey\Uuid\Doctrine\UuidType::NAME => \Ramsey\Uuid\Doctrine\UuidType::class,
                ]
            ],
        ],
        'driver' => [
            'orm_default' => [
                'class' => \Doctrine\Persistence\Mapping\Driver\MappingDriverChain::class,
                'drivers' => [
                    'App\Entity' => 'my_entities',
                ],
            ],
            'my_entities' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AttributeDriver::class,
                'paths' => [__DIR__ . '/../../src/App/Entity'],
            ],
        ],
    ],
];
