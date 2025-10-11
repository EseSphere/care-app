<?php
class Database
{
    private static ?Database $instance = null;
    private mysqli $conn;

    private function __construct()
    {
        $host = getenv("DB_HOST") ?: "localhost";
        $user = getenv("DB_USER") ?: "root";
        $pass = getenv("DB_PASS") ?: "";
        $db   = getenv("DB_NAME") ?: "care_app";

        $this->conn = new mysqli($host, $user, $pass, $db);

        if ($this->conn->connect_error) {
            throw new Exception("Database connection failed: " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8mb4");
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): mysqli
    {
        return $this->conn;
    }
}

$appName = "care_app Care";
$version = "1.0.0";
$author = "care_app";
$appDescription = "A modern healthcare management web application for care services.";
$appTitle = $appName . " - " . $appDescription;
