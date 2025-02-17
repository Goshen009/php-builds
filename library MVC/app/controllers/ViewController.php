<?php

namespace App\Controllers;

use App\Models\Book;
use App\Repositories\BookRepository;
use App\Repositories\UserRepository;
use Core\{Helpers, Router};

class ViewController {
    function __construct(
        private Router $router
    ) { }

    function display(array $data, string $route): void {
        extract($data); // converts the keys of the data array into variables.

        require_once __DIR__ . '/../views/header.php';
        require_once $route;
        require_once __DIR__ . '/../views/footer.php';
    }

    public function index(): void {
        $this->router->redirect(
            url: 'register.php'
        );
    }

    public function display_register(): void {
        Helpers::check_logged_in($this->router); // checks if a user is currently logged in, will go to home.php if a user is logged in.

        $route = __DIR__ . '/../views/auth/register.php';
        $data = [
            'title' => 'Register',
            'inputs' => Helpers::get_session_data('inputs')[0],
            'errors' => Helpers::get_session_data('errors')[0]
        ];

        $this->display($data, $route);
    }

    public function display_login(): void {
        Helpers::check_logged_in($this->router);

        $route = __DIR__ . '/../views/auth/login.php';
        $data = [
            'title' => 'Login',
            'inputs' => Helpers::get_session_data('inputs')[0],
            'errors' => Helpers::get_session_data('errors')[0]
        ];

        $this->display($data, $route);
    }

    public function display_home(BookRepository $bookRepository): void {
        Helpers::require_login($this->router);

        $route = __DIR__ . '/../views/home.php';
        $data = [
            'title' => 'Home',
            'name' => Helpers::get_current_user()->name,
            'isAdmin' => Helpers::get_current_user()->isAdmin,
            'books' => $bookRepository->get_all_books(),
            'borrowedBooks' => $bookRepository->get_books_currently_borrowed_by_user(Helpers::get_current_user()->id)
        ];

        $this->display($data, $route);
    }

    public function display_edit_users(UserRepository $userRepository): void {
        Helpers::require_login($this->router);
        Helpers::require_admin($this->router);

        $route = __DIR__ . '/../views/admin/edit-users.php';
        $data = [
            'title' => 'Edit Users',
            'myId' => Helpers::get_current_user()->id,
            'users' => $userRepository->get_all_users()
        ];

        $this->display($data, $route);
    }

    public function display_edit_profile(): void {
        Helpers::require_login($this->router);

        $route = __DIR__ . '/../views/user/edit-profile.php';
        $data = [
            'title' => 'Edit Profile',
            'name' => Helpers::get_current_user()->name,
            'email' => Helpers::get_current_user()->email,
            'inputs' => Helpers::get_session_data('inputs')[0],
            'errors' => Helpers::get_session_data('errors')[0],
        ];

        $this->display($data, $route);
    }

    public function display_change_password(): void {
        Helpers::require_login($this->router);

        $route = __DIR__ . '/../views/user/change-password.php';
        $data = [
            'title' => 'Change Password',
            'errors' => Helpers::get_session_data('errors')[0],
        ];

        $this->display($data, $route);
    }

    public function display_add_book(): void {
        Helpers::require_login($this->router);
        Helpers::require_admin($this->router);

        $route = __DIR__ . '/../views/admin/add-book.php';
        $data = [
            'title' => 'Edit Users',
            'inputs' => Helpers::get_session_data('inputs')[0],
            'errors' => Helpers::get_session_data('errors')[0],
        ];

        $this->display($data, $route);
    }

    public function display_view_book_details(BookRepository $bookRepository) {
        Helpers::require_login($this->router);

        $route = __DIR__ . '/../views/user/view-book-details.php';
        $data = [
            'title' => 'Book Details',
            'book' => $bookRepository->get_book($_GET['book_id'])
        ];

        $this->display($data, $route);
    }

    public function display_search_for_book() {
        Helpers::require_login($this->router);

        $route = __DIR__ . '/../views/user/search-for-book.php';
        $data = [
            'title' => 'Search For Book',
            'inputs' => Helpers::get_session_data('inputs')[0],
            'errors' => Helpers::get_session_data('errors')[0],
        ];

        $this->display($data, $route);
    }

    public function display_found_books(): void {
        Helpers::require_login($this->router);

        $route = __DIR__ . '/../views/user/found-books.php';
        $data = [
            'title' => 'Found Books',
            'books' => Helpers::get_session_data('books')[0]
        ];

        $this->display($data, $route);
    }

    public function display_edit_book(BookRepository $bookRepository) {
        Helpers::require_login($this->router);
        Helpers::require_admin($this->router);

        $route = __DIR__ . '/../views/admin/edit-book.php';
        $data = [
            'title' => 'Edit Book',
            'book' => $bookRepository->get_book($_GET['book_id']),
            'inputs' => Helpers::get_session_data('inputs')[0],
            'errors' => Helpers::get_session_data('errors')[0],
        ];

        $this->display($data, $route);
    }

    public function display_view_borrow_history(BookRepository $bookRepository) {
        Helpers::require_login($this->router);

        $route = __DIR__ . '/../views/user/view-borrow-history.php';
        $data = [
            'title' => 'Borrow History',
            'history' => $bookRepository->get_borrow_history(Helpers::get_current_user()->id)
        ];

        $this->display($data, $route);
    }
}