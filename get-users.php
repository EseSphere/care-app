<?php
$mysqli = new mysqli("localhost", "root", "", "mydb");
if ($mysqli->connect_error) die("Connection failed");

$res = $mysqli->query("SELECT * FROM users");
$users = $res->fetch_all(MYSQLI_ASSOC);

echo json_encode($users);
$mysqli->close();
