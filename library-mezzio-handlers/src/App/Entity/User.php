<?php

namespace App\Entity;

use App\Enums\UserRole;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User {
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $id;
    
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imageUrl = null;

    public function __construct(
        #[ORM\Column(type: 'string', length: 50, unique: true)]
        private string $username,

        #[ORM\Column(type: "string", length: 255, unique: true)]
        private string $email,

        #[ORM\Column(type: "string", length: 60)]
        private string $passwordHash,

        #[ORM\Column(type: 'string')]
        private string $role = UserRole::Regular->value,

        #[ORM\Column(type: 'boolean')]
        private bool $verified = false,

        #[ORM\OneToMany(targetEntity: BorrowedBook::class, mappedBy: "user", cascade: ["persist", "remove"])]
        private Collection $borrowedBooks = new ArrayCollection()
    ) {
        
    }

    public function getID(): ?UuidInterface {
        return $this->id;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getPasswordHash(): string {
        return $this->passwordHash;    
    }

    public function getRole(): string { 
        return $this->role;    
    }

    public function getBorrowedBooks(): Collection {
        return $this->borrowedBooks;
    }

    public function getImageURL(): ?string {
        return $this->imageUrl;
    }

    public function isVerified(): bool {
        return $this->verified;
    }

    public static function signUp(
        string $username,
        string $email,
        string $passwordHash
    ): self {
        return new static (
            $username,
            $email,
            $passwordHash
        );
    }

    public function updateProfile(?string $username, ?string $email, ?string $passwordHash) {
        if ($username !== null) {
            $this->username = $username;
        }

        if ($email !== null) {
            $this->email = $email;
        }

        if ($passwordHash !== null) {
            $this->passwordHash = $passwordHash;
        }
    }

    public function uploadImage(string $imageUrl) {
        $this->imageUrl = $imageUrl;
    }

    public function changeRole(UserRole $newRole) {
        $this->role = $newRole->value;
    }

    public function addBorrowedBook(BorrowedBook $borrowedBooks): void {
        $this->borrowedBooks->add($borrowedBooks);
    }

    public function verify() {
        $this->verified = true;
    }
}