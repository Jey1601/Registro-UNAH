<?php



include_once "../../../../src/DAO/ApplicantDAO.php";


$dao = new ApplicantDAO();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

   if ($_POST['typeCertificate'] == 'base64') {
      $secondary_certificate_applicant = base64_decode($_POST['applicantCertificate']);
  } else {
      $certificateFile = $_FILES['applicantCertificate'];
      $secondary_certificate_applicant = file_get_contents($certificateFile['tmp_name']);
  }
  
  if ($_POST['typeId'] == 'base64') {
      $image_id_applicant = base64_decode($_POST['applicantIdDocument']);
  } else {
      $idFile = $_FILES['applicantIdDocument'];
      $image_id_applicant = file_get_contents($idFile['tmp_name']);
  }


   $id_applicant = $_POST['applicantIdentification'];
   $first_name = $_POST['applicantFirstName'];
   $second_name = $_POST['applicantSecondName'];
   $third_name = $_POST['applicantThirdName'];
   $first_lastname = $_POST['applicantFirstLastName'];
   $second_lastname = $_POST['applicantSecondLastName'];
   $email = $_POST['applicantEmail'];
   $phone_number = $_POST['applicantPhoneNumber'];
   $address = $_POST['applicantDirection'];
   $id_admission_application_number = $_POST['id_admission_application_number'];
   $id_check_applicant_applications = $_POST['id_check_applicant_applications'];
        // Llamamos al método createInscription para actualizar los datos
        $dao->updateDataApplicant(
           $id_applicant,
           $first_name,
           $second_name,
           $third_name,
           $first_lastname,
           $second_lastname,
           $email,
           $phone_number,
           $address,
           $image_id_applicant,
           $secondary_certificate_applicant,
           $id_admission_application_number,
            $id_check_applicant_applications
        );
   
      


} 

?>