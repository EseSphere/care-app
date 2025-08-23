<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host     = getenv("DB_HOST") ?: "localhost";
$username = getenv("DB_USER") ?: "root";
$password = getenv("DB_PASS") ?: "";
$database = getenv("DB_NAME") ?: "offline_app";

try {
    $mysqli = new mysqli($host, $username, $password, $database);
    $mysqli->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log("DB Connection Failed: " . $e->getMessage());
    http_response_code(500);
    exit("Service Unavailable");
}

$appName = "Care App";
$baseUrl = "/care-app";
$appSlogan = "Software for care settings";
$appDescription = "A simple offline-first web app for care settings";
$appTitle = "$appName - $appSlogan";
