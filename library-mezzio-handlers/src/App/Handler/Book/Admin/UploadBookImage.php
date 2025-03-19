<?php

declare(strict_types=1);

namespace App\Handler\Book\Admin;

use App\Entity\Book;
use App\Services\CloudinaryService;
use Doctrine\ORM\EntityManager;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UploadBookImage implements RequestHandlerInterface
{
    public function __construct(
        private EntityManager $entityManager,
        private CloudinaryService $cloudinaryService
    ) {
        
    }
    
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        /** @var Book */
        $book = $request->getAttribute(Book::class);
        $imageURI = $request->getUploadedFiles()['image']->getStream()->getMetadata('uri');

        $imageURL = $this->cloudinaryService->uploadImage($imageURI, $book->getImageURL(), 'book_images');
        $book->uploadImage($imageURL);

        $this->entityManager->flush();
        return new JsonResponse("Image uploaded successfully. URL is " . $imageURL);
    }
}
