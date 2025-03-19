<?php

declare(strict_types=1);

namespace App\Handler\Book\Admin;

use App\Entity\Book;
use Biblys\Isbn\Isbn;
use Doctrine\ORM\EntityManager;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CreateBook implements RequestHandlerInterface
{
    public function __construct(
        protected EntityManager $entityManager
    ) {
        
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $data = $request->getParsedBody();

        $book = Book::createBook(
            $data['title'],
            $data['author'],
            $data['genre'],
            $data['description'],
            $data['isbn'],
            $data['publication-date'],
            $data['total-copies'],
            $data['available-copies']
        );

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return new JsonResponse("Successfully created a new book with id {$book->getID()}");
    }
}
