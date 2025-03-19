<?php

declare(strict_types=1);

namespace App\Handler\Book;

use App\Entity\Book;
use Doctrine\ORM\EntityManager;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FindAllBooks implements RequestHandlerInterface
{
    public function __construct(
        protected EntityManager $entityManager
    ) {
        
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $books = $this->entityManager->getRepository(Book::class)->findAll();

        $booksData = [];
        foreach ($books as $book) {
            $booksData[] = $book->data();
        }

        return new JsonResponse($booksData);
    }
}
