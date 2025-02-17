<?php

namespace Core;

use PDO;
use Core\Router;

class App {
    function __construct(
        private Router $router,
        PDO $db,
    ){
        $this->load_routes($db);
    }

    private function load_routes(PDO $db) {
        require_once __DIR__ . '/../routes/web.php';
        load_routes($this->router, $db);
    }

    public function run() {
        $this->router->handleRequest();
    }
}