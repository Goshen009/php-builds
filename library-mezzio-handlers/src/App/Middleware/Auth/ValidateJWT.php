<?php

declare(strict_types=1);

namespace App\Middleware\Auth;

use App\Entity\User;
use App\Services\JWT;
use Assert\Assertion;
use Doctrine\ORM\EntityManager;
use Exception;
use Firebase\JWT\ExpiredException;
use InvalidArgumentException;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;

class ValidateJWT implements MiddlewareInterface
{
    public function __construct(
        private ProblemDetailsResponseFactory $problemDetailsFactory,
        private EntityManager $entityManager,
        private JWT $jwt
    ) {
        
    }

    private function getJWTFromHeader(ServerRequestInterface $request): string {
        $authHeader = $request->getHeaderLine('Authorization');

        if (preg_match('/^Bearer\s+(.*)$/i', $authHeader, $matches)) {
            $token = $matches[1];
            return $token;
        } else {
            throw new Exception("Authorization token not found or invalid");
        }
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        try {
            $token = $this->getJWTFromHeader($request);

            $userPayload = $this->jwt->decode($token);

            Uuid::fromString($userPayload->id);

            $user = $this->entityManager->find(User::class, $userPayload->id);
            Assertion::notNull($user, "no user with id {$userPayload->id} found");

            $request = $request->withAttribute(User::class, $user);
            return $handler->handle($request);

        } catch (ExpiredException $e) { // Firebase will automatically check the validity of the token and throws an ExpiredException if it is used after the time
            return $this->problemDetailsFactory->createResponse(
                $request,
                400,
                $e->getMessage()
            );
        } catch (InvalidArgumentException $e) {
            return $this->problemDetailsFactory->createResponse(
                $request,
                400,
                $e->getMessage()
            );
        } catch (InvalidUuidStringException $e) {
            return $this->problemDetailsFactory->createResponse(
                $request,
                400,
                $e->getMessage()
            );
        } catch (Exception $e) {
            return $this->problemDetailsFactory->createResponse(
                $request,
                400,
                $e->getMessage()
            );
        }
    }
}
