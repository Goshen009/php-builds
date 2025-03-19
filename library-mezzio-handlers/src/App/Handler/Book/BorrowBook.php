<?php

declare(strict_types=1);

namespace App\Handler\Book;

use App\Entity\Book;
use App\Entity\BorrowedBook;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManager;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BorrowBook implements RequestHandlerInterface
{
    public function __construct(
        protected EntityManager $entityManager
    ) {
        
    }
    
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        /** @var User @var Book */
        $user = $request->getAttribute(User::class);
        $book = $request->getAttribute(Book::class);

        $borrowDate = new DateTime();
        $dueDate = (new DateTime())->modify("+5 days");

        $borrowingEntry = BorrowedBook::borrowBook(
            $user,
            $book,
            $borrowDate,
            $dueDate
        );

        $book->borrow(); // reduces available book by 1
        $user->addBorrowedBook($borrowingEntry);

        $this->entityManager->persist($borrowingEntry);
        $this->entityManager->flush();

        return new JsonResponse("Book '{$book->getTitle()}' was borrowed by user '{$user->getUsername()}'");
    }
}
