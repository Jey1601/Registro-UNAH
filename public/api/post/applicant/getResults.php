<?php

$path = '../../../../';
include_once $path."src/DAO/ApplicantDAO.php";

$dao = new ApplicantDAO();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

   $id_applicant = $_POST['id_applicant'];
   

        // Llamamos al método createInscription para insertar los datos
        $dao->redirect($id_applicant);
   

} else {
    // Si no es una solicitud POST, respondemos con un error
    echo json_encode(["error" => "Método no permitido. Use POST."]);
}

?>