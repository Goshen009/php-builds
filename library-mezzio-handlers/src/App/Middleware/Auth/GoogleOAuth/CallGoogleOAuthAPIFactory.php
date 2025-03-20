<?php

declare(strict_types=1);

namespace App\Middleware\Auth\GoogleOAuth;

use App\Services\GoogleOAuthService;
use Psr\Container\ContainerInterface;

class CallGoogleOAuthAPIFactory
{
    public function __invoke(ContainerInterface $container) : CallGoogleOAuthAPI
    {
        return new CallGoogleOAuthAPI(
            $container->get(GoogleOAuthService::class)
        );
    }
}
