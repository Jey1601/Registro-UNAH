<?php
    /*
    * @author Alejandro Moya 20211020462
    * @created Noviembre de 2024
    */
header("Content-Type: application/json");

$path = '../../../../';
include_once $path."src/DAO/AcceptanceAdmissionProcessDAO.php";

$daoAcceptanceAdmissionProcess = new AcceptanceAdmissionProcessDAO();

try {
    $isAcceptanceAdmissionProcessOpen = $daoAcceptanceAdmissionProcess->getVerifyAcceptanceAdmissionProcess();

    if ($isAcceptanceAdmissionProcessOpen) {
        echo json_encode([
            "status" => "success",
            "message" => "El proceso de Consultar Resultados de Examen de Admisión está abierto."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "El proceso de Consultar Resultados de Examen de Admisión no está disponible en este momento."
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Ocurrió un error al verificar el proceso de Consultar Resultados de Examen de Admisión: " . $e->getMessage()
    ]);
}

?>
