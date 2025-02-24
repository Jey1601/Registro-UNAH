<?php


$path = '../../../../';
include_once $path."src/DAO/ApplicantDAO.php";


$dao = new ApplicantDAO();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {


   $certificateFile = $_FILES['applicantCertificate'];
   $idFile = $_FILES['applicantIdDocument'];

   $id_applicant = $_POST['applicantIdentification'];
   $first_name = $_POST['applicantFirstName'];
   $second_name = $_POST['applicantSecondName'];
   $third_name = $_POST['applicantThirdName'];
   $first_lastname = $_POST['applicantFirstLastName'];
   $second_lastname = $_POST['applicantSecondLastName'];
   $email = $_POST['applicantEmail'];
   $phone_number = $_POST['applicantPhoneNumber'];
   $address = $_POST['applicantDirection'];
   $status = 1;  
   $id_aplicant_type = 1; //Valor por defecto de aspirantes de nuevo ingreso
   $secondary_certificate_applicant =  file_get_contents($certificateFile['tmp_name']);
   $image_id_applicant =  file_get_contents($idFile['tmp_name']);
   $id_regional_center = $_POST['applicantStudyCenter'];
   $regionalcenter_admissiontest_applicant = $id_regional_center; //Por defecto se mantiene igual, se espera poder realizar el examen en una locación y estudiar en otro
   $intendedprimary_undergraduate_applicant = $_POST['applicantFirstChoice'];
   $intendedsecondary_undergraduate_applicant = $_POST['applicantSecondChoice'];
  

        // Llamamos al método createInscription para insertar los datos
        $dao->createInscription(
           $id_applicant,
           $first_name,
           $second_name,
           $third_name,
           $first_lastname,
           $second_lastname,
           $email,
           $phone_number,
           $address,
           $status,
           $id_aplicant_type,
           $image_id_applicant,
           $secondary_certificate_applicant,
           $id_regional_center,
           $regionalcenter_admissiontest_applicant,
           $intendedprimary_undergraduate_applicant,
           $intendedsecondary_undergraduate_applicant
        );
   
      


} 

?>