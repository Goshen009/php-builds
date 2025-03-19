<?php

declare(strict_types=1);

namespace App\Middleware\Fetch;

use App\Entity\Book;
use Assert\Assertion;
use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;

class FetchBook implements MiddlewareInterface
{
    public function __construct(
        protected ProblemDetailsResponseFactory $problemDetailsFactory,
        protected EntityManager $entityManager
    ) {
        
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $book_id = $request->getAttribute('book_id');

        try {
            Assertion::notNull($book_id, 'the book id cannot be null');

            $book = $this->entityManager->find(Book::class, $book_id);
            Assertion::notNull($book, "No book with id '{$book_id}' found");

            $request = $request->withAttribute(Book::class, $book);
            return $handler->handle($request);
            
        } catch (InvalidArgumentException $e) {
            return $this->problemDetailsFactory->createResponse(
                $request,
                400,
                $e->getMessage()
            );
        } catch (InvalidUuidStringException $e) {
            return $this->problemDetailsFactory->createResponse(
                $request,
                400,
                $e->getMessage()
            );
        }
    }
}