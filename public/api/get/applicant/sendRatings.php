<?php

header("Content-Type: application/json"); 


$path = '../../../../';

include_once $path."src/DAO/util/mail.php";


$mail = new mail();


// Leer el parámetro 'id_center' desde la URL

 
 
   $mail->sendRatings();
   

?>