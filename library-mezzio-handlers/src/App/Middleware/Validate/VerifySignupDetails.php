<?php

declare(strict_types=1);

namespace App\Middleware\Validate;

use App\CustomAssertion;
use App\Entity\User;
use Assert\Assert;
use Assert\Assertion;
use Assert\InvalidArgumentException;
use Doctrine\ORM\EntityManager;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class VerifySignupDetails implements MiddlewareInterface
{
    public function __construct(
        protected ProblemDetailsResponseFactory $problemDetailsFactory,
        private EntityManager $entityManager
    ) {

    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $data = $request->getParsedBody();

        try {
            // Validate Username
            Assertion::notEmptyKey($data, 'username', 'username is required');
            Assert::that($data['username'])
                ->regex('/^[a-zA-Z\d\s]+$/', 'username must be alphanumeric only')
                ->minLength(3, 'username must be at least 3 characters')
                ->maxLength(50, 'username must not be more than 50 characters');
            CustomAssertion::isUnique($data['username'], $this->entityManager, User::class, 'username', 'username already exists');

            // Validate Email
            Assertion::notEmptyKey($data, 'email', 'email is required');
            Assertion::email($data['email'], 'this is not a valid email address');
            CustomAssertion::isUnique($data['email'], $this->entityManager, User::class, 'email', 'email already exists');
            
            // Validate Password
            Assertion::notEmptyKey($data, 'password', 'password is required');
            CustomAssertion::validPassword($data['password']);

            $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);

            $request = $request->withAttribute('password-hash', $passwordHash);
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
