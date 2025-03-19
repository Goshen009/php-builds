<?php

declare(strict_types=1);

namespace App\Middleware\Validate;

use App\Entity\Book;
use App\Entity\BorrowedBook;
use App\Entity\User;
use Assert\Assertion;
use Assert\InvalidArgumentException;
use Doctrine\ORM\EntityManager;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ValidBorrow implements MiddlewareInterface
{
    public function __construct(
        protected ProblemDetailsResponseFactory $problemDetailsFactory,
        protected EntityManager $entityManager
    ) {
        
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $user = $request->getAttribute(User::class);
        $book = $request->getAttribute(Book::class);

        $borrowedBooks = $this->entityManager->getRepository(BorrowedBook::class)->findBy([
            'user' => $user,
            'returnDate' => null
        ]);

        try {
            Assertion::lessThan(count($borrowedBooks), 3, 'return the books you have borrowed in order to borrow another one');

            Assertion::greaterThan($book->getAvailableCopies(), 0, 'there are no available copies');

            foreach ($borrowedBooks as $borrowing) {
                Assertion::notSame($borrowing->getBook()->getID(), $book->getID(), 'you have borrowed this book already');
            }

            return $handler->handle($request);

        } catch (InvalidArgumentException $e) {
            return $this->problemDetailsFactory->createResponse(
                $request, 400, $e->getMessage()
            );
        }
    }
}
