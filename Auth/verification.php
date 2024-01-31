<?php
include '../connection.php';

$phone =  $_POST['phone']; // filterRequest('phone');
$otp = $_POST['otp']; //filterRequest('otp');


$stmt = $pdo->prepare("SELECT * FROM users WHERE `phone` = ? AND `otp` = ?");
$stmt->execute(array($phone ,$otp));


$count = $stmt->rowCount();

if ($count > 0) {


    echo json_encode(array('status'=>'success'));
    // $stmt = $pdo->prepare('UPDATE users SET otp = :otp WHERE phone = :phone');
    // $stmt->execute([
    //     ":otp" => null,
    //     ":phone" => $phone
    // ]);

    # code...
}
else {
    echo json_encode(array('status'=>'fail'));
}

?>