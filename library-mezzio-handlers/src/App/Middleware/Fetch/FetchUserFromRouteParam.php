<?php

declare(strict_types=1);

namespace App\Middleware\Fetch;

use App\Entity\User;
use App\Models\UserFromRoute;
use Assert\Assertion;
use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;

class FetchUserFromRouteParam implements MiddlewareInterface
{
    public function __construct(
        private ProblemDetailsResponseFactory $problemDetailsFactory,
        private EntityManager $entityManager
    ) {
        
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $id = $request->getAttribute('id');

        try {
            Assertion::notNull($id, 'id of user to change is required');

            $userFromRoute = $this->entityManager->find(User::class, $id);
            Assertion::notNull($userFromRoute, "no user with id '{$id}' found");

            $request = $request->withAttribute(UserFromRoute::class, $userFromRoute);
            return $handler->handle($request);

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
