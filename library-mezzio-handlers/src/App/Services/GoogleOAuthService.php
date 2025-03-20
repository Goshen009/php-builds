<?php

declare(strict_types=1);

namespace App\Services;

use Google\Client;
use Google\Service\Oauth2;
use Google\Service\Oauth2\Userinfo;

class GoogleOAuthService {
    public function __construct(
        private Client $client
    ) {
        
    }

    public function getAuthUrl() {
        $this->client->addScope('email');
        $this->client->addScope('profile');

        return $this->client->createAuthUrl();
    }

    public function getUserDataFromGoogle(string $code): Userinfo {
        $token = $this->client->fetchAccessTokenWithAuthCode($code);
        $this->client->setAccessToken($token);

        return (new Oauth2($this->client))->userinfo->get();
    }

    public function login() {
        // $client->setAuthConfig('') try this later
    }
}