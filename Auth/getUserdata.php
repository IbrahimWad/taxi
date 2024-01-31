<?php
include '../connection.php';

$phone = $_POST['phone'];

// Prepare the SQL statement
$sql = 'SELECT * FROM `users` WHERE `phone` = ?';

// Execute the SQL statement with the phone parameter
$stmt = $pdo->prepare($sql);
$stmt->execute([$phone]);

// Fetch the results as an associative array
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if any results were found
if ($results) {
    // Convert the results to JSON format
    $json = json_encode($results);

    // Output the JSON response
    header('Content-Type: application/json');
    echo $json;
} else {
    $response = array('status' => 'error', 'message' => 'No user found with the given phone number');
    echo json_encode($response);
}
?>
