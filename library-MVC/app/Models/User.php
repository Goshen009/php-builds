<?php

namespace App\Models;

class User {
    function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly bool $isAdmin
    ) { }
}