<?php
require_once 'config.php';

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_error) {
            die("Database connection failed: " . $this->connection->connect_error);
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->connection;
    }

    private function __clone() {} // Prevent cloning of the instance
    private function __wakeup() {} // Prevent unserializing of the instance
}

$db = Database::getInstance();
if ($db->connect_error) {
    error_log("Database connection failed in collect_data.php: " . $db->connect_error);
    http_response_code(500);
    echo json_encode(['error' => 'Database connection error']);
    exit;
}
?>