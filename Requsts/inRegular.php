<?php
require __DIR__ . '/../vendor/autoload.php';

use WebSocket\Client as WebSocketClient;

// Prepare the data to send via WebSocket
$data = array(
    'mylatitude' => $_POST['mylatitude'],
    'mylongitude' => $_POST['mylongitude'],
    'latitude' => $_POST['latitude'],
    'longitude' => $_POST['longitude'],
    'phone' => $_POST['phone'],
    'salary' => $_POST['salary'],
    'payment' => $_POST['payment']
);

// Create a WebSocket client instance and establish the connection
$client = new WebSocketClient('ws://192.168.2.107:8080', [
    'timeout' => 50, // Increase the timeout value (in seconds) as needed
]);

// Debug statement: Outputting a message before sending data
echo "Sending data to WebSocket server...\n";

// Send the data to the WebSocket server
$client->send(json_encode($data));

// Debug statement: Outputting a message after sending data
echo "Data sent to WebSocket server.\n";

// Debug statement: Outputting a message before receiving data
echo "Waiting for response from WebSocket server...\n";

// Receive and handle messages from the server
$response = $client->receive();

// Debug statement: Outputting the received response
echo "Received message from server: $response\n";

// Handle the response from the server
$responseData = json_decode($response, true);
// Handle the response according to your needs

// Close the WebSocket connection
$client->close();
