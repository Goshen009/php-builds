<?php

declare(strict_types=1);

namespace App\Handler\User\Admin;

use App\Models\UserFromRoute;
use App\Services\CloudinaryService;
use Doctrine\ORM\EntityManager;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DeleteUser implements RequestHandlerInterface
{
    
    public function __construct(
        private EntityManager $entityManager,
        private CloudinaryService $cloudinaryService
    ) {
        
    }
    
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $userToDelete = $request->getAttribute(UserFromRoute::class);

        $this->entityManager->remove($userToDelete);
        $this->entityManager->flush();

        if ($userToDelete->getImageURL()) {
            $this->cloudinaryService->deleteImage($userToDelete->getImageURL());
        }

        return new JsonResponse("{$userToDelete->getUsername()} was removed");
    }
}
