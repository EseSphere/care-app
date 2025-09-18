<?php
// distance.php
header('Content-Type: application/json');

$apiKey = 'AIzaSyBTWuN9VC9BLvvy2dLJTSlW_AijYf5DIN4';

$origins = urlencode($_GET['origins'] ?? '');
$destinations = urlencode($_GET['destinations'] ?? '');

if (!$origins || !$destinations) {
    echo json_encode(['error' => 'Missing origins or destinations']);
    exit;
}

$url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$origins&destinations=$destinations&key=$apiKey&mode=driving";

$response = file_get_contents($url);
echo $response;
