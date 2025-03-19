<?php

declare(strict_types=1);

namespace App\Handler\Book\Admin;

use App\Entity\Book;
use Doctrine\ORM\EntityManager;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EditBook implements RequestHandlerInterface
{
    public function __construct(
        protected EntityManager $entityManager
    ) {

    }
    
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $data = $request->getParsedBody();
        $file = $request->getUploadedFiles();

        $book = $request->getAttribute(Book::class);
        $book->editBook(
            $data['title'] ?? null,
            $data['author'] ?? null,
            $data['genre'] ?? null,
            $data['description'] ?? null,
            $data['isbn'] ?? null,
            $data['publication-date'] ?? null,
            $data['total-copies'] ?? null,
            $data['available-copies'] ?? null
        );
        
        $this->entityManager->flush();
        return new JsonResponse("The book {$book->getTitle()} was successfully updated");
    }
}
