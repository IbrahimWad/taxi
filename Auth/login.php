<?php 

include '../connection.php';
$sid = "ACa1fa1cbf5ffbad803e2afb493b5e47be"; // Your Account SID from https://console.twilio.com
$token = "724253d2043efd403406009773aee367"; // Your Auth Token from https://console.twilio.com
$client = new Twilio\Rest\Client($sid, $token);
$otp = rand(1000,9999);
$phone = $_POST['phone'];// filterRequest('phone'); //$_POST['username'];
$otpDB =$_POST['otp'];// filterRequest('otp'); //$_POST['password'];
 //filterRequest('name');
$otpDB = $otp;
// function sendOTP($client,$phone, $otp){
// }
if ($_SERVER['REQUEST_METHOD']== 'POST') {
    # code...
    $errors = array();
    if (empty($_POST['phone'])) {
        # code...
        echo json_encode(array('status'=>'Enter your phone number please'));
    }
    else {
        # Insert data to dataBase
        try {
            //code...
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE phone = ?');
            $stmt->execute([$phone]);
            if ($stmt->fetchColumn() > 0) {
                // make login here 
               $stmt = $pdo->prepare("SELECT * FROM users WHERE `phone` = ?");
                $stmt->execute(array($phone));
    
                $count = $stmt->rowCount();
                if ($count > 0) {
                 header('Access-Control-Allow-Origin: *');   
                 //echo $otpDB ;
                 $stmt = $pdo->prepare('UPDATE users SET otp = :otp WHERE phone = :phone');

                 $stmt->execute([
                    ":otp" => $otpDB,
                    ":phone" => $phone
                ]);
                // $message = $client->messages->create(
                //     $phone,
                //     [
                //         'from' => '+15075169476',
                //         'body' => $otp
                //     ]
                //     );
                echo json_encode(array('status'=>'OTP send successfully'));
                }
                else {
                    echo json_encode(array('status'=>'fail'));
                }
                // echo json_encode(array('status'=>'phone is alredy used'));
            }  else {
                $stmt = $pdo->prepare('INSERT INTO users (phone, otp) VALUES (:phone, :otp)');

                $stmt->execute(array(
                    ':phone' => $phone,
                    ':otp' => $otpDB
                ));

               // echo $phone;
            //    $message = $client->messages->create(
            //     $phone,
            //     [
            //         'from' => '+15075169476',
            //         'body' => $otp
            //     ]
            //     );
                   // echo 'OTP sent. SID: ' . $;
                echo json_encode(array('status'=>'OTP send successfully'));
            }
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
               // echo 'Username already exists';
            } else {
                echo 'Error creating user: ' . $e->getMessage();
            }
        }
    }
}
?>