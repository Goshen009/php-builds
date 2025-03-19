<?php

declare(strict_types=1);

namespace App\Handler\User\Register;

use App\Services\JWT;
use Psr\Container\ContainerInterface;

class SendVerificationCodeFactory
{
    public function __invoke(ContainerInterface $container) : SendVerificationCode
    {
        return new SendVerificationCode(
            $container->get(JWT::class)
        );
    }
}
