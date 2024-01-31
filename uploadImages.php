<?php

function imageUpload($imageRequst){
    global $msgError;
    if(isset($_FILES[$imageRequst])){
       $imagename = rand(10000 , 1000000). $_FILES[$imageRequst]['name'];
       $imagetemp = $_FILES[$imageRequst]['tmp_name'];
       $allowExt = array('jpg','png','jpeg');
       $strToArray = explode( '.',$imagename);
       $ext = end($strToArray);
       $ext = strtolower($ext);
 
       if (!empty($imagename) && !in_array($ext, $allowExt)) {
          $msgError[] = 'Ext';
       }
 
       if (empty($msgError)) {
          move_uploaded_file($imagetemp , 'C:\xampp\htdocs\dashboard\taxiDelivery\images\\' . $imagename );
          return $imagename;
       }
       else {
         return 'no image';
       }
    }
    else {
        return 'no image';
    }
 }
 
 
 
 function deleteFile($dir , $imagename){
    if (file_exists($dir . '/' . $imagename)) {
      unlink($dir . '/' . $imagename);
    }
 }


 ?>