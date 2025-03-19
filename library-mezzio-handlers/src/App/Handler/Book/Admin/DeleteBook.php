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

class DeleteBook implements RequestHandlerInterface
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

        $this->entityManager->remove($book);
        $this->entityManager->flush();

        if ($book->getImageURL()) {
            $this->cloudinaryService->deleteImage($book->getImageURL());
        }

        return new JsonResponse("{$book->getTitle()} was removed");
    }
}
