<?php

declare(strict_types=1);

namespace App\Handler\User\Register;

use App\Services\JWT;
use Doctrine\ORM\EntityManager;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

class VerifyVerificationCodeFactory
{
    public function __invoke(ContainerInterface $container) : VerifyVerificationCode
    {
        return new VerifyVerificationCode(
            $container->get(ProblemDetailsResponseFactory::class),
            $container->get(EntityManager::class),
            $container->get(JWT::class),
        );
    }
}
