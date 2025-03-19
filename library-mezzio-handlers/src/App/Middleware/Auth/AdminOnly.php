<?php

declare(strict_types=1);

namespace App\Middleware\Auth;

use App\Entity\User;
use App\Enums\UserRole;
use Assert\Assertion;
use InvalidArgumentException;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AdminOnly implements MiddlewareInterface
{
    public function __construct(
        private ProblemDetailsResponseFactory $problemDetailsFactory,
    ){
        
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        try {
            $user = $request->getAttribute(User::class);
            Assertion::eq($user->getRole(), UserRole::Admin->value, 'only admins can perform this action');

            return $handler->handle($request);
            
        } catch (InvalidArgumentException $e) {
            return $this->problemDetailsFactory->createResponse(
                $request,
                400,
                $e->getMessage()
            );
        }
    }
}
