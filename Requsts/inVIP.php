<?php
include '../connection.php';


$my_latitude = $_POST['mylatitude']; //filterRequest('mylatitude');
$my_longitude = $_POST['mylongitude']; //filterRequest('mylongitude');
$latitude =  $_POST['latitude'];//filterRequest('latitude');
$longitude = $_POST['longitude']; //filterRequest('longitude');
$phone = $_POST['phone']; //filterRequest('phone');
$salary = $_POST['salary'];
$payment = $_POST['payment'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST['mylatitude']) || empty($_POST['mylongitude']) || 
    empty($_POST['latitude']) || empty($_POST['longitude']) || 
    empty($_POST['phone']) || empty($_POST['salary']) ||
    empty($_POST['payment'])) {
        # code...
        echo json_encode(array('status'=> 'please complet your requst'));
    }
   
        // Insert new user into database
    else {   try {
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM invip WHERE phone = ?');
            $stmt->execute([$phone]);

            if ($stmt->fetchColumn() > 0) {
                echo json_encode(array('status'=>'Your Requst have been send already'));
            } else {
               
            
                $stmt = $pdo->prepare('INSERT INTO invip (mylatitude, mylongitude, latitude, longitude, phone, salary, payment) 
                VALUES (:mylatitude, :mylongitude, :latitude, :longitude, :phone, :salary, :payment)');
                $stmt->execute(array(
                    ':mylatitude' => $my_latitude,
                    ':mylongitude' => $my_longitude,
                    ':latitude' => $latitude,
                    ':longitude' => $longitude,
                    ':phone' => $phone,
                    ':salary' => $salary,
                    ':payment'=>$payment
                ));

                echo json_encode(array('status'=>'Requst has been send successfully'));
            }
        } catch(PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                echo 'your requst has been sent alredy';
            } else {
                echo 'Error sending requst: ' . $e->getMessage();
            }
        }
    }
    
}
?>
