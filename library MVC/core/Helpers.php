<?php

namespace Core;

use App\Models\User;

class Helpers {
    public static function is_user_logged_in(): bool {
        return isset($_SESSION['user']);
    }

    public static function get_current_user() {
        return new User(...$_SESSION['user']);
    }

    public static function check_logged_in(Router $router) {
        if (static::is_user_logged_in() === true) {
            $router->redirect(
                url: 'home.php',
            );
        }
    }

    public static function require_login(Router $router) {
        if (static::is_user_logged_in() === false) {
            $router->redirect(
                url: 'login.php'
            );
        }
    }

    public static function require_admin(Router $router) {
        if (static::get_current_user()->isAdmin === false) {
            $router->redirect(
                url: 'home.php'
            );
        }
    }

    public static function get_session_data(...$keys): array {
        $session_data = [];

        foreach ($keys as $key) {
            if (isset($_SESSION[$key])) {
                $session_data[] = $_SESSION[$key];
                unset($_SESSION[$key]);
            } else {
                $session_data[] = []; // if you don't add an empty array, then the output will be all jumbled up.
            }
        }
        return $session_data;
    }
}

?>