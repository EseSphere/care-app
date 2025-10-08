<?php
header('Content-Type: application/json');
error_reporting(E_ERROR | E_PARSE);

require_once('dbconnection.php');

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database connection failed: " . $e->getMessage()]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$email = isset($input['email']) ? trim($input['email']) : '';

if (!$email) {
    echo json_encode(["success" => false, "message" => "Email is required."]);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT * FROM tbl_goesoft_carers_account WHERE user_email_address = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode([
            "exists" => true,
            "user" => $user
        ]);
    } else {
        echo json_encode([
            "exists" => false,
            "message" => "Email not found. Please sign up first."
        ]);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Server error: " . $e->getMessage()]);
}
exit;
