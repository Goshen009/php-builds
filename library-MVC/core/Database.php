<?php
namespace Core;

use PDO, PDOException;

class Database {
    private $pdo;

    function __construct(string $db_host, string $db_name, string $db_user, string $db_password) {
        try {
            $dsn = sprintf("mysql:host=%s;dbname=%s;charset=UTF8", $db_host, $db_name);
            $this->pdo = new PDO($dsn, $db_user, $db_password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (PDOException $error) {
            die("Connection failed: " . $error->getMessage());
        }
    }

    public function get_pdo(): PDO { return $this->pdo; }
}