<?php
include_once "../../../../src/DAO/ApplicantDAO.php";


$daoApplicant = new ApplicantDAO();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $_POST = json_decode(file_get_contents('php://input'), true);
    $idCheckApplicant = $_POST['idCheckApplicant'];
    $verificationStatus = $_POST['verificationStatus'];
    $revision_status = $_POST['revisionStatus'];
    $descriptionGeneralCheck = $_POST['descriptionGeneralCheck'];
    $errorData = $_POST['errorData'];
    $result = $daoApplicant->updateCheckApplicant($idCheckApplicant, $verificationStatus, $revision_status,$descriptionGeneralCheck, $errorData);
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Applicant check actualizado satisfactoriamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el  applicant check']);
    }
} else {
    echo json_encode(["error" => "Metodo incorrecto. Metodo esperado:  POST"]);
}

?>