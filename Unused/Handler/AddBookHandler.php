<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AddBookHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        // Create and return a response
    }
}
