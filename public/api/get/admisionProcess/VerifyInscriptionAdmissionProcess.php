<?php
    /*
    * @author Alejandro Moya 20211020462
    * @created Noviembre de 2024
    */
header("Content-Type: application/json");

$path = '../../../../';

include_once $path."src/DAO/InscriptionAdmissionProcessDAO.php";

$daoInscriptionAdmissionProcess = new InscriptionAdmissionProcessDAO();

try {
    $isInscriptionAdmissionProcessOpen = $daoInscriptionAdmissionProcess->getVerifyInscriptionAdmissionProcess();

    if ($isInscriptionAdmissionProcessOpen) {
        echo json_encode([
            "status" => "success",
            "message" => "El proceso de inscripciones está abierto."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "El proceso de inscripciones no está disponible en este momento."
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Ocurrió un error al verificar el proceso de inscripciones: " . $e->getMessage()
    ]);
}

?>
