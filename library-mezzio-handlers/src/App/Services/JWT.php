<?php

declare(strict_types=1);

namespace App\Services;

use Assert\Assertion;
use DateInterval;
use DateTime;
use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;
use stdClass;

class JWT {
    public function __construct(
        private array $config
    ) {
        
    }
    
    public function generate(string $id): string {
        $payload = [
            'iss' => $this->config['claim']['iss'],
            'aud' => $this->config['claim']['aud'],
            'iat' => (new DateTime())->getTimestamp(),
            'exp' => (new DateTime())->add(new DateInterval($this->config['expiryPeriod']))->getTimestamp(),
            'user' => [
                'id' => $id
            ]
        ];

        $jwt = FirebaseJWT::encode($payload, $this->config['key'], $this->config['alg']);
        return $jwt;
    }

    public function decode(string $jwt): stdClass {
        FirebaseJWT::$leeway = $this->config['leeway'];
        $payload = FirebaseJWT::decode($jwt, new Key($this->config['key'], $this->config['alg']));

        Assertion::eq($payload->iss, $this->config['claim']['iss'], 'invalid issuer');
        Assertion::eq($payload->aud, $this->config['claim']['aud'], 'invalid audience');

        return $payload->user;
    }
}