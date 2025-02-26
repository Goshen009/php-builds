<?php

namespace Core;

use PDO;
use Core\Router;

class App {
    function __construct(
        private Router $router,
        FlashMessages $flashMessages,
        Filter $filter,
        PDO $db,
    ){
        $this->load_routes($flashMessages, $filter, $db);
    }

    private function load_routes(FlashMessages $flashMessages, Filter $filter, PDO $db) {
        require_once __DIR__ . '/../routes/web.php';
        load_routes($this->router, $flashMessages, $filter, $db);
    }

    public function run() {
        $this->router->handleRequest();
    }
}