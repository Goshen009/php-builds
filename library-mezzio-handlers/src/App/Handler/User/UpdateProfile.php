<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UpdateProfile implements RequestHandlerInterface
{
    public function __construct(
        protected EntityManager $entityManager
    ) {

    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $passwordHash = $request->getAttribute('password-hash');
        $data = $request->getParsedBody();

        $user = $request->getAttribute(User::class);
        $user->updateProfile(
            $data['username'] ?? null,
            $data['email'] ?? null,
            $passwordHash ?? null
        );

        $this->entityManager->flush();
        return new JsonResponse("The user {$user->getID()} was successfully updated");
    }
}
