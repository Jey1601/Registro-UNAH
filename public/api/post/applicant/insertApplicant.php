<?php



include_once "../../../../src/DAO/ApplicantDAO.php";


$dao = new ApplicantDAO("localhost", "root", "your_password", "unah_registration");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {


   $file = $_FILES['aplicantCertificate'];

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
   $id_admission_process= 1; //Debe leerse dinamicamente de la base de datos
   $id_aplicant_type = 1; //Valor por defecto de aspirantes de nuevo ingreso
   $secondary_certificate_applicant =  file_get_contents($file['tmp_name']);
   $id_regional_center = $_POST['applicantStudyCenter'];
   $regionalcenter_admissiontest_applicant = $id_regional_center; //Por defecto se mantiene igual, se espera poder realizar el examen en una locación y estudiar en otro
   $intendedprimary_undergraduate_applicant = $_POST['applicantFirstChoice'];
   $intendedsecondary_undergraduate_applicant = $_POST['applicantSecondChoice'];
   $status_application = 1; // por defecto como verdadero

        // Llamamos al método insertApplicant para insertar los datos
        $dao->insertApplicant(
           $id_applicant,
           $first_name,
           $second_name,
           $third_name,
           $first_lastname,
           $second_lastname,
           $email,
           $phone_number,
           $address,
           $status
        );

        $dao->insertApplication(
           $id_admission_process,
           $id_applicant,
           $id_aplicant_type,
           $secondary_certificate_applicant,
           $id_regional_center,
           $regionalcenter_admissiontest_applicant,
           $intendedprimary_undergraduate_applicant,
           $intendedsecondary_undergraduate_applicant,
           $status_application
        );
    


} else {
    // Si no es una solicitud POST, respondemos con un error
    echo json_encode(["message" => "Método no permitido. Use POST."]);
}

?>