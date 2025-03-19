<?php

declare(strict_types=1);

namespace App\Middleware\SignUp;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SignUpUniqueFilterMiddleware implements MiddlewareInterface
{
    public function __construct(
        private EntityManager $entityManager
    ) {
        
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $data = $request->getParsedBody();

        $hasEmailBeenUsed = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $data['email']]) !== null;
        
        $hasUsernameBeenUsed = $this->entityManager->getRepository(User::class)
            ->findOneBy(['username' => $data['username']]) !== null;

        if ($hasEmailBeenUsed || $hasUsernameBeenUsed) {
            return new JsonResponse([
                'error' => 'Invalid request data',
                'details' => $this->getErrorMessage($hasEmailBeenUsed, $hasUsernameBeenUsed)
            ], 400);
        }

        $response = $handler->handle($request);
        return $response;
    }

    public function getErrorMessage(bool $hasEmailBeenUsed, bool $hasUsernameBeenUsed): array {
        $message = [];
            
        if ($hasEmailBeenUsed) {
            $message[] = "This email is already in use";
        }

        if ($hasUsernameBeenUsed) {
            $message[] = "This username is already in use";
        }

        return $message;
    }
}
