<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: 'borrowedBooks')]
class BorrowedBook {
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $id;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?DateTime $returnDate = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $fine = null;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "borrowedBook")]
        #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
        private User $user,

        #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: "borrowedBook")]
        #[ORM\JoinColumn(name: "book_id", referencedColumnName: "id", nullable:false, onDelete: "CASCADE")]
        private Book $book,

        #[ORM\Column(type: "datetime")]
        private DateTime $borrowDate,

        #[ORM\Column(type: "datetime")]
        private DateTime $dueDate
    ) {
        
    }

    public function getBook(): Book {
        return $this->book;
    }
    public function getDueDate(): DateTime {
        return $this->dueDate;
    }

    public static function borrowBook(
        User $user,
        Book $book,
        DateTime $borrowDate,
        DateTime $dueDate
    ): self {
        return new static (
            $user,
            $book,
            $borrowDate,
            $dueDate
        );
    }

    public function returnBook(DateTime $returnDate, ?int $fine): void {
        $this->returnDate = $returnDate;

        if ($fine !== null) {
            $this->fine = $fine;
        }
    }
}