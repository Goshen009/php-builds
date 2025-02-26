<?php
session_start();

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use Core\{App, Router, Database, Filter, FlashMessages};
use Core\Filter\{Sanitation, Validation};

$database = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);

$sanitation = new Sanitation;
$validation = new Validation;
$filter = new Filter($sanitation, $validation);

$flashMessages = new FlashMessages;
$router = new Router($flashMessages);

$app = new App($router, $flashMessages, $filter, $database->get_pdo());
$app->run();

function print_this($var, $name='') {
    echo "<pre>$name\n";
    var_dump($var);
    echo '</pre>';
}