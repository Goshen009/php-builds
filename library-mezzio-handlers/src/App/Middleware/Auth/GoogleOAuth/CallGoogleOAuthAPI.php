<?php

declare(strict_types=1);

namespace App\Middleware\Auth\GoogleOAuth;

use App\Services\GoogleOAuthService;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CallGoogleOAuthAPI implements MiddlewareInterface
{
    public function __construct(
        private GoogleOAuthService $googleOAuthService
    ) {
        
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $url = $this->googleOAuthService->getAuthUrl();
        
        return new RedirectResponse($url);
    }
}
