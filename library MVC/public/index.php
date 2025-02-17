<?php
session_start();

require '../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use Core\{App, Router, Database};

$database = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
$router = new Router();

$app = new App($router, $database->get_pdo());
$app->run();

function print_this($var, $name='') {
    echo "<pre>$name\n";
    var_dump($var);
    echo '</pre>';
}