<?php

declare(strict_types=1);

namespace App\Handler\User\Auth;

use App\Services\JWT;
use Psr\Container\ContainerInterface;

class LoginFactory
{
    public function __invoke(ContainerInterface $container) : Login
    {
        return new Login(
            $container->get(JWT::class)
        );
    }
}
