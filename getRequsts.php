<?php


function getRequsts($sql){
include '../connection.php';
//$sql = 'SELECT * FROM `inregular`';
$stmt = $pdo->prepare($sql);
$stmt->execute(); 
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($results) {
    // Convert the results to JSON format
    $json = json_encode($results);

    // Output the JSON response
    header('Content-Type: application/json');
    echo $json;
} else{
    echo json_encode(array('status'=>'No requsts'));
}

}

// $sql = 'SELECT * FROM `inregular`';
// $stmt = $pdo->prepare($sql);
// $stmt->execute(); 
// $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// if ($results) {
//     // Convert the results to JSON format
//     $json = json_encode($results);

//     // Output the JSON response
//     header('Content-Type: application/json');
//     echo $json;
// } else{
//     echo json_encode(array('status'=>'No requsts'));
// }

?>