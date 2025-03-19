<?php

declare(strict_types=1);

namespace App\Handler\Book;

use App\Entity\Book;
use App\Entity\BorrowedBook;
use App\Entity\Borrowing;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManager;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ReturnBook implements RequestHandlerInterface
{
    public function __construct(
        protected EntityManager $entityManager
    ) {
        
    }
    
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $user = $request->getAttribute(User::class);
        $book = $request->getAttribute(Book::class);
        $borrowedBook = $request->getAttribute(BorrowedBook::class);

        $returnDate = new DateTime();

        if ($returnDate > $borrowedBook->getDueDate()) {
            $interval = $borrowedBook->getDueDate()->diff($returnDate)->days;
            $fine = $interval * 10;
        }

        $borrowedBook->returnBook($returnDate, $fine ?? null);
        $book->return();

        $this->entityManager->flush();
        return new JsonResponse("User '{$user->getUsername()}' returned '{$book->getTitle()}'");
    }
}
