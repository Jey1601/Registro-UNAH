<?php



include_once "../../../../../src/DAO/ApplicantDAO.php";


$dao = new ApplicantDAO();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {



  $id_applicant_acceptance = $_POST['option'];
  $primaryResolution = $_POST['primaryResolution'];
  $secondaryResolution = $_POST['secondaryResolution'];

        // Llamamos al método createInscription para insertar los datos
        $dao->registerAcceptance($id_applicant_acceptance, $primaryResolution, $secondaryResolution);
   
        


} else {
    // Si no es una solicitud POST, respondemos con un error
    echo json_encode(["error" => "Método no permitido. Use POST."]);
}

?>