<?php

declare(strict_types=1);

namespace App\Handler\User\Register;

use App\Entity\User;
use App\Services\JWT;
use Assert\Assertion;
use Doctrine\ORM\EntityManager;
use Firebase\JWT\ExpiredException;
use InvalidArgumentException;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;

class VerifyVerificationCode implements MiddlewareInterface
{
    public function __construct(
        private ProblemDetailsResponseFactory $problemDetailsFactory,
        private EntityManager $entityManager,
        private JWT $jwt
    ) {
        
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $data = $request->getQueryParams();

        try {
            Assertion::notEmptyKey($data, 'verify_code', 'no verification code');
            $verificationCode = $data['verify_code'];

            $userPayload = $this->jwt->decode($verificationCode);

            Uuid::fromString($userPayload->id);

            /** @var User */
            $user = $this->entityManager->find(User::class, $userPayload->id);
            Assertion::notNull($user, "no user with id {$userPayload->id} found");

            if ($user->isVerified()) {
                return new JsonResponse("This user is already verified");
            }

            $request = $request->withAttribute(User::class, $user);
            return $handler->handle($request);

        } catch (ExpiredException $e) {
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
        }
    }
}
