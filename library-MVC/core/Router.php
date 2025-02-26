<?php

namespace Core;

use App\Enums\FlashMessageEnum;

class Router {
    private $routes = [];

    function __construct(
        private FlashMessages $flashMessages
    ) {
        
    }

    public function POST(string $uri, callable $handler, array $parameters=[]) {
        $this->routes['POST'][$uri] = [$handler, $parameters];
    }

    public function GET(string $uri, callable $handler, array $parameters=[]) {
        $this->routes['GET'][$uri] = [$handler, $parameters];
    }

    public function handleRequest() {
        [$uri] = explode('?', $_SERVER['REQUEST_URI']);
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method][$uri])) {
            list($handler, $parameters) = $this->routes[$method][$uri];
            call_user_func($handler, ...$parameters);
        } else {
            echo "$uri -> 404 Page Not Found";
        }
    }

    public function redirect(string $url, array $items=[], string $message='', FlashMessageEnum $type=FlashMessageEnum::FLASH_SUCCESS): void {
        if (!empty($items)) {
            foreach ($items as $key => $value) {
                $_SESSION[$key] = $value; // adds the items to the session so it can be used on redirect
            }
        }

        if (!empty($message)) {
            $this->flashMessages->flash('flash_' . uniqid(), $message, $type->value);
        }
         
        header('Location:' . $url);
        exit;
    }
}