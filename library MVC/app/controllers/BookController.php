<?php

namespace App\Controllers;

use PDO;
use Core\{Router, Flash, Filter, Helpers};
use App\Repositories\BookRepository;

class BookController {
    function __construct(
        private PDO $db,
        private BookRepository $bookRepository,
        private Router $router
    ) { }

    public function add_book() {
        $filters = [
            'title' => ['string', 'required', 'alphanumeric', 'between: 3, 50', 'unique: books, title'],
            'author' => ['string', 'required', 'alphanumeric', 'between: 3, 50'],
            'genre' => ['string', 'required', 'alphanumeric', 'between: 3, 50'],
            'isbn' => ['string', 'required', 'alphanumeric', 'between: 14, 17', 'unique: books, isbn'],
            'description' => ['string', 'required', 'alphanumeric', 'between: 0, 150'],
            'publication-date' => ['int', 'required', 'min: 1900', 'max: 2155'],
            'total-copies' => ['int', 'required', 'min: 1'],
        ];

        $media_filters = [
            'book-image' => [
                'allowed-types' => ['jpg', 'jpeg', 'png'],
                'max-size' => 5 * 1024 * 1024 //5mb
            ]
        ];

        [$inputs, $errors] = Filter::filter($this->db, $_POST, $filters);
        [$media_inputs, $media_errors] = Filter::filter_image($this->db, $_POST, $media_filters);

        $errors = array_merge($errors, $media_errors);
        $inputs = array_merge($inputs, $media_inputs);

        if ($errors) {
            $this->router->redirect(url: 'add-book.php', items: [
                'inputs' => $inputs,
                'errors' => $errors
            ]);
        }

        $this->bookRepository->add_book($inputs);
        $this->router->redirect(
            url: 'home.php',
            message: 'You have successfully added a book to the library'
        );
    }

    public function borrow_book() {
        $userId = Helpers::get_current_user()->id;
        $bookId = $_GET['book_id'];
        $title = $_GET['title'];

        if ($this->bookRepository->get_available_books($bookId) <= 0) {
            $this->router->redirect(
                url: 'home.php',
                message: 'No available copies of this book',
                type: Flash::FLASH_ERROR
            );
        }

        $borrowedBooks =  $this->bookRepository->get_books_currently_borrowed_by_user($userId);
        if (in_array($bookId, $borrowedBooks)) {
            $this->router->redirect(
                url: 'home.php',
                message: 'You have borrowed this book already!',
                type: Flash::FLASH_ERROR
            );
        }

        if (count($borrowedBooks) >= 3) {
            $this->router->redirect(
                url: 'home.php',
                message: 'You cannot borrow more than 3 books',
                type: Flash::FLASH_ERROR
            );
        }

        $this->bookRepository->borrow_book($userId, $bookId, $title);
        $this->router->redirect(
            url: 'home.php',
            message: 'Book successfully borrowed'
        );
    }

    public function return_book() {
        $userId = Helpers::get_current_user()->id;
        $bookId = $_GET['book_id'];

        $borrowedBooks =  $this->bookRepository->get_books_currently_borrowed_by_user($userId);
        if (!in_array($bookId, $borrowedBooks)) {
            $this->router->redirect(
                url: 'home.php',
                message: 'You did not even borrow this book',
                type: Flash::FLASH_ERROR
            );
        }

        $this->bookRepository->return_book($userId, $bookId);
        $this->router->redirect(
            url: 'home.php',
            message: 'Book successfully returned'
        );
    }
    
    public function search_for_book() {
        $filters = [];

        // what is happening here?
        // the user can choose to search on either the title, author, genre... or any combination of these three.
        // meaning that it's only on the ones with a value that we need a filter.

        foreach (['title', 'author', 'genre'] as $field) {
            if (trim($_POST[$field]) !== '') {
                $filters[$field] = ['string', 'required', 'alphanumeric', 'between: 3, 50'];
            }
        }

        [$inputs, $errors] = Filter::filter($this->db, $_POST, $filters);

        if ($errors) {
            $this->router->redirect(url: 'search-for-book.php', items: [
                'inputs' => $inputs,
                'errors' => $errors
            ]);
        }

        $books = $this->bookRepository->search_for_book($inputs['title'] ?? '', $inputs['author'] ?? '', $inputs['genre'] ?? '');
        $this->router->redirect(url: 'found-books.php', items: [
            'books' => $books
        ]);
    }

    public function edit_book() {
        $filters = [
            'title' => ['string', 'required', 'alphanumeric', 'between: 3, 50', "changed: {$_GET['title']},  books, title"],
            'author' => ['string', 'required', 'alphanumeric', 'between: 3, 50'],
            'genre' => ['string', 'required', 'alphanumeric', 'between: 3, 50'],
            'isbn' => ['string', 'required', 'alphanumeric', 'between: 14, 17', "changed: {$_GET['isbn']},  books, isbn"],
            'description' => ['string', 'required', 'alphanumeric', 'between: 0, 150'],
            'publication-date' => ['int', 'required', 'min: 1900', 'max: 2155'],
        ];

        [$inputs, $errors] = Filter::filter($this->db, $_POST, $filters);

        if ($errors) {
            $this->router->redirect(url: "edit-book.php?book_id={$_GET['id']}", items: [
                'inputs' => $inputs,
                'errors' => $errors
            ]);
        }

        $this->bookRepository->edit_book($_GET['id'], $inputs);
        $this->router->redirect(
            url: 'home.php',
            message: "You have edited the book successfully."
        );
    }

    public function delete_book() {
        $this->bookRepository->delete_book($_GET['book_id']);

        $this->router->redirect(
            url: 'home.php',
            message: "You have deleted the book from the database."
        );
    }
}