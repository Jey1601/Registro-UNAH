<?php



include_once "../../../../src/DAO/ApplicantDAO.php";


$dao = new ApplicantDAO();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {



  $id_applicant_acceptance = $_POST['option'];


        // Llamamos al método createInscription para insertar los datos
        $dao->registerAcceptance($id_applicant_acceptance);
   
        


} else {
    // Si no es una solicitud POST, respondemos con un error
    echo json_encode(["error" => "Método no permitido. Use POST."]);
}

?>