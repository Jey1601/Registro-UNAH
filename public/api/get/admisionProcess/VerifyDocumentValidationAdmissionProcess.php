<?php
    /*
    * @author Alejandro Moya 20211020462
    * @created Noviembre de 2024
    */
header("Content-Type: application/json");

$path = '../../../../';
include_once $path."src/DAO/DocumentValidationAdmissionProcessDAO.php";

$daoDocumentValidationAdmissionProcess = new DocumentValidationAdmissionProcessDAO();

try {
    $isDocumentValidationAdmissionProcessOpen = $daoDocumentValidationAdmissionProcess->getVerifyDocumentValidationAdmissionProcess();

    if ($isDocumentValidationAdmissionProcessOpen) {
        echo json_encode([
            "status" => "success",
            "message" => "El proceso de admisión está abierto."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "El proceso de admisión no está disponible en este momento."
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Ocurrió un error al verificar el proceso de admisión: " . $e->getMessage()
    ]);
}

?>
