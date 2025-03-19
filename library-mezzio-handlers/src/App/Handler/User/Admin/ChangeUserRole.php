<?php

declare(strict_types=1);

namespace App\Handler\User\Admin;

use App\Enums\UserRole;
use App\Models\UserFromRoute;
use Doctrine\ORM\EntityManager;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ChangeUserRole implements RequestHandlerInterface
{
    public function __construct(
        protected EntityManager $entityManager
    ) {
        
    }
    
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $userToChange = $request->getAttribute(UserFromRoute::class);
        $role = $request->getAttribute(UserRole::class);

        $userToChange->changeRole($role);

        $this->entityManager->flush();
        return new JsonResponse("{$userToChange->getUsername()}'s role has been switched to {$role->value}");
    }
}
