<?php
$path = '../../../../';
include_once $path."src/DAO/ApplicantDAO.php";


$daoApplicant = new ApplicantDAO();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $_POST = json_decode(file_get_contents('php://input'), true);
    $idCheckApplicant = (int)$_POST['idCheckApplicant'];
    $verificationStatus = (int)$_POST['verificationStatus'];
    $revision_status = (int)$_POST['revisionStatus'];
    $descriptionGeneralCheck = $_POST['descriptionGeneralCheck'] ?? '';

    $errorData = $_POST['errorData'];
    // Convertir la cadena en un arreglo usando explode
    $errorDataArray = explode(', ', $errorData);
    $result = $daoApplicant->updateCheckApplicant($idCheckApplicant, $verificationStatus, $revision_status,$descriptionGeneralCheck, $errorDataArray);
    if ($result) {
        echo json_encode($result);
        //echo json_encode(['success' => true, 'message' => 'Applicant check actualizado satisfactoriamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el  applicant check']);
    }
} else {
    echo json_encode(["error" => "Metodo incorrecto. Metodo esperado:  POST"]);
}

?>