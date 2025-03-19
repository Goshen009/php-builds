<?php

declare(strict_types=1);

namespace App\Middleware\Validate;

use App\CustomAssertion;
use App\Entity\User;
use App\Services\JWT;
use Assert\Assertion;
use Assert\InvalidArgumentException;
use Doctrine\ORM\EntityManager;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

class VerifyLoginDetails implements MiddlewareInterface
{
    public function __construct(
        protected ProblemDetailsResponseFactory $problemDetailsFactory,
        private EntityManager $entityManager,
        private JWT $jwt
    ) {

    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $data = $request->getParsedBody();

        try {
            // Validating Username
            Assertion::notEmptyKey($data, 'username', 'username is required');
            Assertion::regex($data['username'], '/^[a-zA-Z\d\s]+$/', 'This is an invalid username');
            CustomAssertion::existsInDatabase($data['username'], $this->entityManager, User::class, 'username', "This username does not exist");

            // Validating Password
            Assertion::notEmptyKey($data, 'password', 'password is required');
            
            /** @var User */
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $data['username']]);

            if (!password_verify($data['password'], $user->getPasswordHash())) {
                throw new RuntimeException('incorrect username or password');
            }

            if (!$user->isVerified()) {
                $verificationCode = $this->jwt->generate($user->getID()->toString());
                $verificationLink = "http://localhost:8080/users/verify?verify_code=$verificationCode";

                // send a real email instead

                return new JsonResponse("You have not been verified. Verify your email with this link: {$verificationLink}");
            }

            $request = $request->withAttribute(User::class, $user);
            return $handler->handle($request);

        } catch (InvalidArgumentException | RuntimeException $e ) {
            return $this->problemDetailsFactory->createResponse(
                $request,
                400,
                $e->getMessage()
            );
        }
    }
}
