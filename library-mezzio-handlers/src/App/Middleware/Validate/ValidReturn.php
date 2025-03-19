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

class ValidReturn implements MiddlewareInterface
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

        $borrowedBook = $this->entityManager->getRepository(BorrowedBook::class)->findOneBy([
            'user' => $user,
            'book' => $book,
            'returnDate' => null
        ]);

        try {
            Assertion::lessThan($book->getAvailableCopies(), $book->getTotalCopies(), 'this book does not belong to us, we have all our copies');

            Assertion::notNull($borrowedBook, 'you are trying to return a book you never borrowed');

            $request = $request->withAttribute(BorrowedBook::class, $borrowedBook);
            return $handler->handle($request);

        } catch (InvalidArgumentException $e) {
            return $this->problemDetailsFactory->createResponse(
                $request, 400, $e->getMessage()
            );
        }
    }
}
