<?php

declare(strict_types=1);

namespace App\Handler\User\Auth;

use App\Entity\User;
use App\Services\JWT;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Login implements RequestHandlerInterface
{
    public function __construct(
        private JWT $jwt
    ) {
        
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        /** @var User */
        $user = $request->getAttribute(User::class);
        
        $jwt = $this->jwt->generate($user->getID()->toString());

        $response = new JsonResponse('successfully logged into account');
        $response = $response->withHeader('Authorization', 'Bearer ' . $jwt);

        return $response;
    }
}
