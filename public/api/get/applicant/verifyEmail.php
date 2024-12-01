<?php

header("Content-Type: application/json"); 


$path = '../../../../';

include_once $path."src/DAO/util/mail.php";


$mail = new mail();


// Leer el parámetro 'id_center' desde la URL

    $confirmation_code_email_confirmation = $_GET['emailCodeVerification'];
    $applicant_id_email_confirmation = $_GET['applicantIdentification'];

 
 
   $mail->getConfirmationEmailApplicants($applicant_id_email_confirmation,$confirmation_code_email_confirmation);
   

?>