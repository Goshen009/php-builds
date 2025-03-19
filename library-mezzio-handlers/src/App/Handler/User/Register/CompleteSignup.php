<?php

declare(strict_types=1);

namespace App\Handler\User\Register;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CompleteSignup implements MiddlewareInterface
{
    public function __construct(
        private EntityManager $entityManager
    ) {

    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $user = $request->getAttribute(User::class);

        $user->verify();

        $this->entityManager->flush();
        return new JsonResponse("{$user->getUsername()} has been verified");
    }
}
