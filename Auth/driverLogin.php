<?php
include '../connection.php';

$sid = "ACa1fa1cbf5ffbad803e2afb493b5e47be"; // Your Account SID from https://console.twilio.com
$token = "724253d2043efd403406009773aee367"; // Your Auth Token from https://console.twilio.com
$client = new \Twilio\Rest\Client($sid, $token);

$otp = rand(1000, 9999);
$phone = $_POST['phone'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($phone)) {
        echo json_encode(array('status' => 'Enter your phone number please'));
    } else {
        try {
            $stmt = $pdo->prepare('SELECT * FROM driver WHERE phone = ?');
            $stmt->execute([$phone]);
            $driver = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($driver) {
                if ($driver['isVerified'] == 1) {
                    $stmt = $pdo->prepare('UPDATE driver SET otp = :otp WHERE phone = :phone');
                    $stmt->execute([
                        ":otp" => $otp,
                        ":phone" => $phone
                    ]);

                    // $message = $client->messages->create(
                    //     $phone,
                    //     [
                    //         'from' => '+15075169476',
                    //         'body' => $otp
                    //     ]
                    // );

                    echo json_encode(array('status' => 'OTP sent successfully', 'login' => true));
                } else {
                    echo json_encode(array('status' => 'Cannot login. Phone number is not verified', 'login' => false));
                }
            } else {
                echo json_encode(array('status' => 'Cannot login. Phone number does not exist', 'login' => false));
            }
        } catch (\Throwable $th) {
            // Handle the exception
        }
    }
}
?>
