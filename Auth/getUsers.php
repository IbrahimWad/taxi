<?php
include '../connection.php';

// Prepare the SQL statement
$sql = 'SELECT * FROM `users` WHERE 1';

// Execute the SQL statement with the phone parameter
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Fetch the results as an associative array
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Encode the results as a JSON map
$json = json_encode(['users' => $results]);

// Set the appropriate header to indicate JSON content
header('Content-Type: application/json');

// Output the JSON data
echo $json;
?>
