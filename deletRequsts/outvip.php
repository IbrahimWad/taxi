<?php
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $phone = $_POST['phone']; // Get the "phone" value from the query string
        
        if ($phone !== null) {
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM outvip WHERE phone = ?');
            $stmt->execute([$phone]);

            if ($stmt->fetchColumn() > 0) {
                $sql = "DELETE FROM outvip WHERE phone = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$phone]);

                echo json_encode(array('status' => 'The travel has been deleted.'));
            } else {
                echo json_encode(array('status' => 'No matching travel found.'));
            }
        } else {
            echo json_encode(array('status' => 'Invalid phone value.'));
        }
    } catch (\Throwable $th) {
        echo json_encode(array('status' => 'An error occurred.'));
    }
}
?>
