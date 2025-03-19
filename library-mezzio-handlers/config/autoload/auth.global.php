<?php
declare(strict_types=1);

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

return [
    'jwt' => [
        'key' => $_ENV['JWT_KEY'],
        'claim' => [
            'iss' => 'http://localhost:8080',
            'aud' => 'http://localhost:8080'
        ],
        'expiryPeriod' => 'P50D',
        'leeway' => 60,
        'alg' => 'HS256',
    ]
];