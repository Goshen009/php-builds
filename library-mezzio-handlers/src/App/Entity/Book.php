<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: 'books')]
class Book {
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imageUrl = null;

    public function __construct (
        #[ORM\Column(type: 'string', length: 50, unique: true)]
        private string $title,

        #[ORM\Column(type: "string", length: 50)]
        private string $author,

        #[ORM\Column(type: "string", length: 50)]
        private string $genre,

        #[ORM\Column(type: "string", length: 255)]
        private string $description,

        #[ORM\Column(type: "string")]
        private string $isbn,

        #[ORM\Column(type: "integer")]
        private int $publicationDate,

        #[ORM\Column(type: "integer")]
        private int $totalCopies,

        #[ORM\Column(type: "integer")]
        private int $availableCopies,

        #[ORM\OneToMany(targetEntity: BorrowedBook::class, mappedBy: 'book', cascade: ["persist", "remove"])]    
        private Collection $borrowedBook = new ArrayCollection()
    ) {
      
    }

    public static function createBook(
        string $title,
        string $author,
        string $genre,
        string $description,
        string $isbn,
        int $publicationDate,
        int $totalCopies,
        int $availableCopies
    ): self {
        return new static (
            $title,
            $author,
            $genre,
            $description,
            $isbn,
            $publicationDate,
            $totalCopies,
            $availableCopies
        );
    }

    public function getID(): ?UuidInterface { 
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getTotalCopies(): int { 
        return $this->totalCopies;
    }

    public function getAvailableCopies(): int { 
        return $this->availableCopies; 
    }

    public function getImageURL(): ?string {
        return $this->imageUrl;
    }

    public function data(): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'genre' => $this->genre,
            'description' => $this->description,
            'isbn' => $this->isbn,
            'publicationDate' => $this->publicationDate,
            'totalCopies' => $this->totalCopies,
            'availableCopies' => $this->availableCopies,
        ];
    }

    public function borrow() {
        $this->availableCopies -= 1;
    }

    public function return() {
        $this->availableCopies += 1;
    }

    public function uploadImage(string $imageUrl) {
        $this->imageUrl = $imageUrl;
    }

    public function editBook(
        ?string $title,
        ?string $author,
        ?string $genre,
        ?string $description,
        ?string $isbn,
        ?int $publicationDate,
        ?int $totalCopies,
        ?int $availableCopies
    ) {
        if ($title !== null) {
            $this->title = $title;
        }

        if ($author !== null) {
            $this->author = $author;
        }

        if ($genre !== null) {
            $this->genre = $genre;
        }

        if ($description !== null) {
            $this->description = $description;
        }

        if ($isbn !== null) {
            $this->isbn = $isbn;
        }

        if ($publicationDate !== null) {
            $this->publicationDate = $publicationDate;
        }

        if ($totalCopies !== null) {
            $this->totalCopies = $totalCopies;
        }

        if ($availableCopies !== null) {
            $this->availableCopies = $availableCopies;
        }
    }
}