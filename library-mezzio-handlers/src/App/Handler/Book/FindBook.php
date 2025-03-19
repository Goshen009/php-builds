<?php

declare(strict_types=1);

namespace App\Handler\Book;

use App\Entity\Book;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FindBook implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $book = $request->getAttribute(Book::class);
        
        return new JsonResponse($book->data());
    }
}
