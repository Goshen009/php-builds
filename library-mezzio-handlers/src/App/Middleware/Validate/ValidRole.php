<?php

declare(strict_types=1);

namespace App\Middleware\Validate;

use App\Enums\UserRole;
use Assert\Assertion;
use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use ValueError;

class ValidRole implements MiddlewareInterface
{
    public function __construct(
        private ProblemDetailsResponseFactory $problemDetailsFactory,
        private EntityManager $entityManager
    ) {
        
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $data = $request->getParsedBody();

        try {
            Assertion::notEmptyKey($data, 'role', 'role is required');
            $role = UserRole::from($data['role']);

            $request = $request->withAttribute(UserRole::class, $role);
            return $handler->handle($request);

        } catch (InvalidArgumentException $e) {
            return $this->problemDetailsFactory->createResponse(
                $request,
                400,
                $e->getMessage()
            );
        } catch (ValueError $e) {
            return $this->problemDetailsFactory->createResponse(
                $request,
                400,
                'this is not a valid role'
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
