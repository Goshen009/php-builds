<?php

use Core\Router;
use App\Controllers\{AuthController, BookController, UserController, ViewController};
use App\Repositories\{BookRepository, InventoryRepository, UserRepository};

function load_routes(Router $router, PDO $db) {
    $userRepository = new UserRepository($db);
    $bookRepository = new BookRepository($db);

    $authController = new AuthController($db, $userRepository, $router);
    $bookController = new BookController($db, $bookRepository, $router);
    $userController = new UserController($db, $userRepository, $router);
    $viewController = new ViewController($router);

    load_auth_routes($router, $authController);
    load_book_routes($router, $bookController);
    load_edit_user_routes($router, $userController);
    load_view_routes($router, $viewController, $userRepository, $bookRepository);
}

function load_view_routes(Router $router, ViewController $viewController, UserRepository $userRepository, BookRepository $bookRepository) {
    $router->GET('/index.php', [$viewController, 'index']);

    $router->GET('/register.php', [$viewController, 'display_register']);
    $router->GET('/login.php', [$viewController, 'display_login']);

    $router->GET('/home.php', [$viewController, 'display_home'], [$bookRepository]);

    $router->GET('/edit-users.php', [$viewController, 'display_edit_users'], [$userRepository]);
    $router->GET('/edit-profile.php', [$viewController, 'display_edit_profile']);
    $router->GET('/change-password.php', [$viewController, 'display_change_password']);

    $router->GET('/add-book.php', [$viewController, 'display_add_book']);
    $router->GET('/edit-book.php', [$viewController, 'display_edit_book'], [$bookRepository]);
    $router->GET('/view-book-details.php', [$viewController, 'display_view_book_details'], [$bookRepository]);
    $router->GET('/view-borrow-history.php', [$viewController, 'display_view_borrow_history'], [$bookRepository]);

    $router->GET('/search-for-book.php', [$viewController, 'display_search_for_book']);
    $router->GET('/found-books.php', [$viewController, 'display_found_books']);
}

function load_auth_routes(Router $router, AuthController $authController) {
    $router->GET('/logout.php', [$authController, 'logout']);

    $router->POST('/register.php', [$authController, 'register']);
    $router->POST('/login.php', [$authController, 'login']);
}

function load_edit_user_routes(Router $router, UserController $userController) {
    $router->GET('/make-admin.php', [$userController, 'make_admin']);
    $router->GET('/make-regular.php', [$userController, 'make_regular']);
    $router->GET('/delete-user.php', [$userController, 'delete_user']);

    $router->POST('/edit-profile.php', [$userController, 'edit_profile']);
    $router->POST('/change-password.php', [$userController, 'change_password']);
}

function load_book_routes(Router $router, BookController $bookController) {
    $router->POST('/add-book.php', [$bookController, 'add_book']);
    $router->POST('/edit-book.php', [$bookController, 'edit_book']);
    $router->POST('/search-for-book.php', [$bookController, 'search_for_book']);

    $router->GET('/borrow-book.php', [$bookController, 'borrow_book']);
    $router->GET('/return-book.php', [$bookController, 'return_book']);
    $router->GET('/delete-book.php', [$bookController, 'delete_book']);
}