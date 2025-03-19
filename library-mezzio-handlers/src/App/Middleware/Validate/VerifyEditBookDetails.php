<?php

declare(strict_types=1);

namespace App\Middleware\Validate;

use App\CustomAssertion;
use App\Entity\Book;
use Assert\Assert;
use Assert\Assertion;
use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class VerifyEditBookDetails implements MiddlewareInterface
{
    public function __construct(
        protected ProblemDetailsResponseFactory $problemDetailsFactory,
        protected EntityManager $entityManager
    ) {

    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $book = $request->getAttribute(Book::class);
        $data = $request->getParsedBody();

        try {
            if (isset($data['title'])) {
                Assert::that($data['title'])
                    ->regex('/^[a-zA-Z\d\s]+$/', 'title must be alphanumeric only')
                    ->minLength(3, 'title must be at least 3 characters')
                    ->maxLength(50, 'title must not be more than 50 characters');
                CustomAssertion::isUnique($data['title'], $this->entityManager, Book::class, 'title', 'title is existing');
            }

            if (isset($data['author'])) {
                Assert::that($data['author'])
                    ->regex('/^[a-zA-Z\d\s.]+$/', "author's name must be alphanumeric only")
                    ->minLength(3, "author's name must be at least 3 characters")
                    ->maxLength(50, "author's name must not be more than 50 characters");
            }

            if (isset($data['genre'])) {
                Assert::that($data['genre'])
                    ->regex('/^[a-zA-Z\d\s]+$/', 'genre must be alphanumeric only')
                    ->minLength(3, 'genre must be at least 3 characters')
                    ->maxLength(50, 'genre must not be more than 50 characters');
            }

            if (isset($data['description'])) {
                Assert::that($data['description'])
                    ->regex('/^[a-zA-Z\d\s,.!"]+$/', "description must not contain special characters")
                    ->minLength(3, "description must be at least 3 characters")
                    ->maxLength(255, "description must not be more than 255 characters");
            }

            if (isset($data['isbn'])) {
                Assert::that($data['isbn'])
                    ->length(13, 'ISBN must be 13 characters long');
                CustomAssertion::isUnique($data['isbn'], $this->entityManager, Book::class, 'isbn', 'This isbn has been used');
            }

            if (isset($data['publication-date'])) {
                Assert::that($data['publication-date'])
                    ->integer('publication date must be an integer')
                    ->range(1900, 2100, 'publication date must not be less than 1900 or greater than 2100');
            }

            if (isset($data['total-copies'])) {
                Assert::that($data['total-copies'])
                    ->integer('total copies must be an integer')
                    ->min(1, 'total copies must be at least 1');
            }

            if (isset($data['available-copies'])) {
                Assertion::integer($data['available-copies'], 'available copies must be an integer');

                if (isset($data['total-copies'])) {
                    Assertion::max($data['available-copies'], $data['total-copies'], 'available copies cannot be more than the total copies');
                } else {
                    Assertion::max($data['available-copies'], $book->getTotalCopies(), 'available copies cannot be more than the total copies');
                }
            }

            return $handler->handle($request);

        } catch (InvalidArgumentException $e) {
            return $this->problemDetailsFactory->createResponse(
                $request,
                400,
                $e->getMessage()
            );
        }
    }
}
