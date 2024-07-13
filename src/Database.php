<?php

namespace App;

use PDO;

class Database {
    private $pdo;

    public function __construct() {
        $dsn = 'mysql:host=localhost;dbname=url_shortener';
        $username = 'root';
        $password = '';
        $this->pdo = new PDO($dsn, $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->initializeDatabase();
    }

    private function initializeDatabase() {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS url_mapping (
            id INT AUTO_INCREMENT PRIMARY KEY,
            short_code VARCHAR(6) UNIQUE,
            long_url TEXT
        )");
    }

    public function insertUrlMapping($shortCode, $longUrl) {
        $stmt = $this->pdo->prepare("INSERT INTO url_mapping (short_code, long_url) VALUES (:short_code, :long_url)");
        $stmt->execute(['short_code' => $shortCode, 'long_url' => $longUrl]);
    }

    public function getLongUrl($shortCode) {
        $stmt = $this->pdo->prepare("SELECT long_url FROM url_mapping WHERE short_code = :short_code");
        $stmt->execute(['short_code' => $shortCode]);
        return $stmt->fetchColumn();
    }
}
