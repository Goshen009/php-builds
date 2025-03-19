<?php

declare(strict_types=1);

namespace App\Handler\User\Auth;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Signup implements RequestHandlerInterface
{
    public function __construct(
        protected EntityManager $entityManager
    )
    {
        
    }
    
    public function handle(ServerRequestInterface $request) : ResponseInterface
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

        return new JsonResponse("Created a new user with id {$user->getID()}");
    }
}
