<?php

namespace App\Models;

class Book {
    function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $author,
        public readonly string $isbn,
        public readonly int $publicationDate,
        public readonly string $genre,
        public readonly string $image,
        public readonly string $description,

        public readonly int $availableCopies,
        public readonly int $totalCopies,
    ) {

    }
}