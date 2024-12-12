<?php

header("Content-Type: application/json"); 


$path = '../../../../';

include_once $path."src/DAO/util/mail.php";


$mail = new mail();




 
 
   $mail->sendRatings();
   

?>