<?php
include '../connection.php';

$sql = 'SELECT * FROM `inregular`';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($results) {
    // Convert the results to JSON format
    $json = json_encode($results);

    // Include the WebSocket client library
    require __DIR__ . '/../vendor/autoload.php';

    // Create a WebSocket client instance and specify the WebSocket URL
    $client = new WebSocket\Client('ws://192.168.2.107:8080');

    // Connect to the WebSocket server
    $client->connect();

    // Send the JSON data to the WebSocket server
    $client->send($json);

    // Close the WebSocket connection
    $client->close();
}
