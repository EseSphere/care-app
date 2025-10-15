<?php
$mysqli = new mysqli("localhost", "root", "", "mydb");
if ($mysqli->connect_error) die("Connection failed");

$username = $_POST['username'] ?? '';
$pin = $_POST['pin'] ?? '';

$stmt = $mysqli->prepare("SELECT * FROM users WHERE name=? AND pin=?");
$stmt->bind_param("ss", $username, $pin);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $user = $res->fetch_assoc();
    echo json_encode(["success" => true, "user" => $user]);
} else {
    echo json_encode(["success" => false]);
}
$stmt->close();
$mysqli->close();
