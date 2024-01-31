<?php
include '../connection.php';

$color = $_POST['carColor'];
$carType = $_POST['carType'];
$carYear = $_POST['carYear'];
$phone = $_POST['phone'];

if ($_SERVER['REQUEST_METHOD']== 'POST') {
    if (empty($color)||empty($carType)||empty($carYear)||empty($phone)) {
        echo json_encode(array('status' => 'Please Enter all data'));
        # code...
    }
    else{
        try {
            $stmt = $pdo->prepare('UPDATE driver SET carColor = :carColor,
             carType = :carType, carYear = :carYear WHERE phone = :phone');

            $stmt->execute([
               ':phone'=>$phone,
               ':carColor'=>$color,
               ':carType'=>$carType,
               ':carYear'=>$carYear,
            ]);
            echo json_encode(array('status'=>'Data has been inserted successfully'));
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            echo json_encode(array('status'=> $th.' '.'SomeThing went wrong'));
        }
    }
}
?>