<?php



include_once "../../../../src/DAO/util/mail.php";


$mail = new mail();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {


   $name = $_POST['applicantName'].' '.$_POST['applicantLastName'];
   $email = $_POST['applicantEmail'];
   $applicant_id_email_confirmation = $_POST['applicantIdentification'];
   
  
    
   $mail->setConfirmationEmailApplicants($name ,$email, $applicant_id_email_confirmation);
      


} 

?>