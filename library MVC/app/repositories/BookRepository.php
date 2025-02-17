<?php

namespace App\Repositories;

use PDO;
use PDOException;
use App\Models\Book;
use DateTime;

class BookRepository{
    function __construct(
        private PDO $db,
    ) {

    }

    public function get_all_books($all_books=[]): array {
        try {
            $this->db->beginTransaction();

            $sql = 'SELECT * FROM books';
            $statement = $this->db->query($sql);
            $statement->execute();

            while ($book = $statement->fetch(PDO::FETCH_ASSOC)) {
                $sql = 'SELECT totalCopies, availableCopies FROM inventory WHERE bookId=:bookId';
                $inventory_statement = $this->db->prepare($sql);
                $inventory_statement->execute([
                    ':bookId' => $book['id']
                ]);

                $inventory = $inventory_statement->fetch(PDO::FETCH_ASSOC);
                $all_books[] = new Book(...$book, ...$inventory);
            }

            $this->db->commit();
            return $all_books;

        } catch (PDOException $e) {
            $this->db->rollBack();
            die ($e->getMessage());
        }
    }

    public function get_book(int $id): Book {
        try {
            $this->db->beginTransaction();

            $sql = 'SELECT * FROM books WHERE id=:id';
            $statement = $this->db->prepare($sql);
            $statement->execute([
                ':id' => $id
            ]);

            $sql = 'SELECT totalCopies, availableCopies FROM inventory WHERE bookId=:bookId';
            $inventory_statement = $this->db->prepare($sql);
            $inventory_statement->execute([
                ':bookId' => $id
            ]);

            $book = $statement->fetch(PDO::FETCH_ASSOC);
            $inventory = $inventory_statement->fetch(PDO::FETCH_ASSOC);

            $this->db->commit();
            return new Book(...$book, ...$inventory);

        } catch (PDOException $e) {
            $this->db->rollBack();
            die ($e->getMessage());
        }
    }

    public function get_books_currently_borrowed_by_user(int $userId): array {
        try {
            $sql = 'SELECT bookId FROM borrowing WHERE userId=:userId AND returnDate IS NULL';
            $statement = $this->db->prepare($sql);
            $statement->execute([
                ':userId' => $userId
            ]);
    
            return $statement->fetchAll(PDO::FETCH_COLUMN, 0) ?? [];
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function get_borrow_history(int $userId): array {
        try {
            $sql = 'SELECT * FROM borrowing WHERE userId=:userId';
            $statement = $this->db->prepare($sql);
            $statement->execute([
                ':userId' => $userId
            ]);
    
            return $statement->fetchAll(PDO::FETCH_ASSOC) ?? [];
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function get_available_books(int $bookId): int {
        try {
            $sql = 'SELECT availableCopies FROM inventory WHERE bookId=:bookId';
            $statement = $this->db->prepare($sql);
            $statement->execute([
                ':bookId' => $bookId
            ]);

            return $statement->fetch(PDO::FETCH_COLUMN) ?? 0;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function add_book(array $inputs) {
        try {
            $this->db->beginTransaction();

            $sql = 'INSERT INTO books(title, author, isbn, publicationDate, genre, image, description)
                    VALUES(:title, :author, :isbn, :publicationDate, :genre, :image, :description)';

            $statement = $this->db->prepare($sql);
            $statement->execute([
                ':title' => $inputs['title'],
                ':author' => $inputs['author'],
                ':isbn' => $inputs['isbn'],
                ':publicationDate' => $inputs['publication-date'],
                ':genre' => $inputs['genre'],
                ':image' => $inputs['book-image'],
                ':description' => $inputs['description']
            ]);


            $inventory_sql = 'INSERT INTO inventory(bookId, totalCopies, availableCopies)
                              VALUES(:bookId, :totalCopies, :availableCopies)';

            $inventory_statement = $this->db->prepare($inventory_sql);
            $inventory_statement->execute([
                ':bookId' => $this->db->lastInsertId(),
                ':totalCopies' => $inputs['total-copies'],
                ':availableCopies' => $inputs['total-copies']
            ]);

            $this->db->commit();

        } catch (PDOException $e) {
            $this->db->rollBack();
            die ("Failed to add book: " . $e->getMessage());
        }
    }

    public function borrow_book(int $userId, int $bookId, string $title) {
        try {
            $this->db->beginTransaction();
          
            $sql = 'UPDATE inventory SET availableCopies = availableCopies - 1
                    WHERE bookId=:bookId AND availableCopies > 0';
            $statement = $this->db->prepare($sql);
            $statement->execute([
                ':bookId' => $bookId
            ]);

            $sql = 'INSERT INTO borrowing(userId, bookId, bookTitle, borrowDate, dueDate)
                    VALUES(:userId, :bookId, :bookTitle, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 5 DAY))';
            $borrowing_statement = $this->db->prepare($sql);
            $borrowing_statement->execute([
                ':userId' => $userId,
                ':bookId' => $bookId,
                ':bookTitle' => $title
            ]);

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            die($e->getMessage());
        }
    }

    public function return_book(int $userId, int $bookId) {
        try {
            $this->db->beginTransaction();

            $sql = 'UPDATE inventory SET availableCopies = availableCopies + 1
                    WHERE bookId=:bookId AND availableCopies < totalCopies';
            $statement = $this->db->prepare($sql);
            $statement->execute([
                ':bookId' => $bookId
            ]);

            $sql = 'UPDATE borrowing SET returnDate = CURDATE(), fine = GREATEST(DATEDIFF(CURDATE(), dueDate), 0) * 500
                    WHERE userId=:userId AND bookId=:bookId';
            $borrowing_statement = $this->db->prepare($sql);
            $borrowing_statement->execute([
                ':userId' => $userId,
                ':bookId' => $bookId
            ]);            

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            die($e->getMessage());
        }
    }

    public function search_for_book(string $title='', string $author='', string $genre=''): array {
        if ($title === '' && $author === '' && $genre === '' )  {
            return [];
        }

        try {
            $sql = 'SELECT * FROM books WHERE title=:title OR author=:author OR genre=:genre';
            $statement = $this->db->prepare($sql);
            $statement->execute([
                ':title' => $title,
                ':author' => $author,
                ':genre' => $genre
            ]);

            return $statement->fetchAll(PDO::FETCH_ASSOC) ?? [];
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function edit_book(int $id, array $inputs) {
        try {
            $sql = 'UPDATE books
                    SET title=:title, author=:author, isbn=:isbn, publicationDate=:publicationDate, genre=:genre, description=:description
                    WHERE id=:id';

            $statement = $this->db->prepare($sql);
            $statement->execute([
                ':id' => $id,
                ':title' => $inputs['title'],
                ':author' => $inputs['author'],
                ':isbn' => $inputs['isbn'],
                ':publicationDate' => $inputs['publication-date'],
                ':genre' => $inputs['genre'],
                ':description' => $inputs['description']
            ]);
            
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function delete_book(int $id) {
        try { // a thought here. if you delete entirely from books, then when checking on the user's borrowing history it'll bring up some issues.
              // eh, i simply added the title of the book to the borrowing table.
            $this->db->beginTransaction();

            $sql = 'DELETE FROM books WHERE id=:id';
            $statement = $this->db->prepare($sql);
            $statement->execute([
                ':id' => $id
            ]);

            $sql = 'DELETE FROM inventory WHERE bookId=:bookId';
            $inventory_statement = $this->db->prepare($sql);
            $inventory_statement->execute([
                ':bookId' => $id
            ]);

            $this->db->commit();

        } catch (PDOException $e) {
            $this->db->rollBack();
            die($e->getMessage());
        }
    }
}