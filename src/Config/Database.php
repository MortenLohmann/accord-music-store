<?php
namespace App\Config;

class Database {
    private static $instance = null;
    private $connection = null;

    private function __construct() {
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $name = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];

        try {
            $this->connection = new \mysqli($host, $user, $pass, $name, $port);
            $this->connection->set_charset("utf8mb4");
        } catch (\Exception $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new \Exception("Database connection failed");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function __destruct() {
        if ($this->connection !== null) {
            $this->connection->close();
        }
    }
} 