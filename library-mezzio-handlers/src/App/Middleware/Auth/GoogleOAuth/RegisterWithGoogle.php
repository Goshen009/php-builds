<?php

declare(strict_types=1);

namespace App\Middleware\Auth\GoogleOAuth;

use App\Entity\User;
use Assert\Assertion;
use Assert\InvalidArgumentException;
use Doctrine\ORM\EntityManager;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RegisterWithGoogle implements MiddlewareInterface
{
    public function __construct(
        private ProblemDetailsResponseFactory $problemDetailsFactory,
        private EntityManager $entityManager
    ){
        
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $data = $request->getQueryParams();

        try {
            Assertion::notEmptyKey($data, 'email', 'no email sent');

            $user = User::signUpWithGoogle(
                $data['username'] ?? '',
                $data['email']
            );

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $request = $request->withAttribute(User::class, $user);
            return $handler->handle($request);

        } catch (InvalidArgumentException $e) {
            return $this->problemDetailsFactory->createResponse($request, 400, $e->getMessage());
        }
    }
}
