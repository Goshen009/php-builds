<?php
declare(strict_types=1);

namespace App\Services;

use Google\Client;
use Psr\Container\ContainerInterface;

class GoogleOAuthServiceFactory {
    public function __invoke(ContainerInterface $container): GoogleOAuthService {
        $config = $container->get('config')['googleOAuth'];

        $client = new Client();
        $client->setClientId($config['clientId']);
        $client->setClientSecret($config['clientSecret']);
        $client->setRedirectUri($config['redirectURI']);

        return new GoogleOAuthService(
            $client
        );
    }
}
