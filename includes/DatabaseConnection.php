<?php
require_once 'config.php';

class DatabaseConnection {

    public static function createConnection() {
        $host = DB_HOST;
        $database = DB_DATABASE;
        $username = DB_USERNAME;
        $password = DB_PASSWORD;

        $dsn = "mysql:host=$host;dbname=$database";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            return new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

}
















































?>