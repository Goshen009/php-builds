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
            // Username
            Assertion::notEmptyKey($data, 'username', 'username is required');
            Assert::that($data['username'])
                ->regex('/^[a-zA-Z\d\s]+$/', 'username must be alphanumeric only')
                ->minLength(3, 'username must be at least 3 characters')
                ->maxLength(50, 'username must not be more than 50 characters');

            // Email
            Assertion::notEmptyKey($data, 'email', 'email is required');
            Assertion::email($data['email'], 'this is not a valid email address');
            $existingEmail = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
            Assertion::null($existingEmail, 'email already exists');

            // Password
            Assertion::notEmptyKey($data, 'password', 'password is required');
            Assert::that($data['password'])
                ->minLength(8, 'Password must be at least 8 characters long.')
                ->maxLength(64, 'Password must not exceed 64 characters.')
                ->regex('/[A-Z]/', 'Password must contain at least one uppercase letter.')
                ->regex('/[a-z]/', 'Password must contain at least one lowercase letter.')
                ->regex('/[\W]/', 'Password must contain at least one special character.')
                ->regex('/\d/', 'Password must contain at least one number.');

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
