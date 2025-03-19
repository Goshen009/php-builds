<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\Entity\User;
use App\Services\CloudinaryService;
use Doctrine\ORM\EntityManager;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UploadProfileImage implements RequestHandlerInterface
{
    public function __construct(
        private EntityManager $entityManager,
        private CloudinaryService $cloudinaryService
    ) {
        
    }
    
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        /** @var User */
        $user = $request->getAttribute(User::class);
        $imageURI = $request->getUploadedFiles()['image']->getStream()->getMetadata('uri');

        $imageURL = $this->cloudinaryService->uploadImage($imageURI, $user->getImageURL(), 'user_profile_images');
        $user->uploadImage($imageURL);

        $this->entityManager->flush();
        return new JsonResponse("{$user->getUsername()}'s profile picture successfully uploaded. URL is " . $imageURL);
    }
}
