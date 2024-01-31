<?php
include '../uploadImages.php';
include '../connection.php';

$name = $_POST['name'];
$phone = $_POST['phone'];

$image1 = imageUpload('image1');
$image2 = imageUpload('image2');
$image3 = imageUpload('image3');

// Check if all three images were uploaded successfully
if ($image1 !== 'no image' || $image2 !== 'no image' || $image3 !== 'no image') {
    try {
        // Establish the database connection

        // Check if the phone number already exists
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM driver WHERE phone = :phone");
        $checkStmt->bindParam(':phone', $phone);
        $checkStmt->execute();
        $rowCount = $checkStmt->fetchColumn();

        if ($rowCount === 0) {
            // Phone number is unique, perform the insertion
            $stmt = $pdo->prepare("INSERT INTO driver (name, phone, drivingLicense, carLicense, idCart, isVerified) 
            VALUES (:name, :phone, :image1, :image2, :image3, false)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':image1', $image1);
            $stmt->bindParam(':image2', $image2);
            $stmt->bindParam(':image3', $image3);
            $stmt->execute();

            echo json_encode(array('status' => 'Data inserted successfully'));
        } else {
            echo json_encode(array('status' => 'Phone number already exists'));
        }
    } catch (PDOException $e) {
        echo "Error inserting data: " . $e->getMessage();
    } finally {
        // Close the database connection
        // $conn = null;
    }
} else if ($image1 == 'no image' || $image2 == 'no image' || $image3 == 'no image' || empty($name) || empty($phone)) {
    echo json_encode(array('status' => 'Insert all data please'));
}
