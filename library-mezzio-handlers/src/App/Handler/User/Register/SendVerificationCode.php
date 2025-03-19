<?php

declare(strict_types=1);

namespace App\Handler\User\Register;

use App\Entity\User;
use App\Services\JWT;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SendVerificationCode implements MiddlewareInterface
{
    public function __construct(
        private JWT $jwt
    ){
        
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $id = $request->getAttribute(User::class)->getID()->toString();

        $verificationCode = $this->jwt->generate($id);

        $verificationLink = "http://localhost:8080/users/verify?verify_code=$verificationCode";
        
        // send a real email instead and then a response.

        return new JsonResponse("Verify your email using this link: {$verificationLink}");
    }
}
