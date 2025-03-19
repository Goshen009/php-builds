<?php
declare(strict_types=1);

namespace App\DTO;

class SignUpDTO {
    public function __construct(
        public readonly string $username,
        public readonly string $email
    ) {
        
    }
}