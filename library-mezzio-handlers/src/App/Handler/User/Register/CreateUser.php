<?php

declare(strict_types=1);

namespace App\Handler\User\Register;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CreateUser implements MiddlewareInterface
{
    public function __construct(
        private EntityManager $entityManager
    ) {
        
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $passwordHash = $request->getAttribute('password-hash');
        $data = $request->getParsedBody();

        $user = User::signUp(
            $data['username'],
            $data['email'],
            $passwordHash
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $request = $request->withAttribute(User::class, $user);
        return $handler->handle($request);
    }
}
