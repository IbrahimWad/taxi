<?php

// Assuming this is your endpoint URL: http://example.com/upload-image-endpoint.php

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the name and phone parameters are present
    if (isset($_POST['name']) && isset($_POST['phone'])) {
        $name = $_POST['name'];
        $phone = $_POST['phone'];

        // Check if the name and phone are not empty
        if (!empty($name) && !empty($phone)) {
            // Include the necessary files
            include '../uploadImages.php';
            include '../connection.php';

            // Check if the user has an image
            $stmt = $pdo->prepare('SELECT `image` FROM `users` WHERE `phone` = ?');
            $stmt->execute(array($phone));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $image = $result['image'];

            if ($image != null) {
                // User has an image
                if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
                    // Delete the old image
                    deleteFile('C:\xampp\htdocs\dashboard\taxiDelivery\images\\', $image);

                    // Upload the new image
                    $imagename = imageUpload('file');

                    if ($imagename != 'no image') {
                        // Update the user's name and new image
                        $stmt = $pdo->prepare('UPDATE `users` SET `name` = ?, `image` = ? WHERE `phone` = ?');
                        $stmt->execute(array(
                            $name,
                            $imagename,
                            $phone
                        ));

                        $response = array('status' => 'success', 'message' => 'Image updated successfully');
                        echo json_encode($response);
                    } else {
                        $response = array('status' => 'error', 'message' => 'Image upload failed');
                        echo json_encode($response);
                    }
                } else {
                    $response = array('status' => 'error', 'message' => 'No image file provided');
                    echo json_encode($response);
                }
            } else {
                // User does not have an image
                if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
                    // Upload the new image
                    $imagename = imageUpload('file');

                    if ($imagename != 'no image') {
                        // Update the user's name and new image
                        $stmt = $pdo->prepare('UPDATE `users` SET `name` = ?, `image` = ? WHERE `phone` = ?');
                        $stmt->execute(array(
                            $name,
                            $imagename,
                            $phone
                        ));

                        $response = array('status' => 'success', 'message' => 'Image uploaded successfully');
                        echo json_encode($response);
                    } else {
                        $response = array('status' => 'error', 'message' => 'Image upload failed');
                        echo json_encode($response);
                    }
                } else {
                    $response = array('status' => 'error', 'message' => 'No image file provided');
                    echo json_encode($response);
                }
            }
        } else {
            $response = array('status' => 'error', 'message' => 'Please enter your name and phone');
            echo json_encode($response);
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Missing name or phone parameter');
        echo json_encode($response);
    }
} else {
    $response = array('status' => 'error', 'message' => 'Invalid request method');
    echo json_encode($response);
}
