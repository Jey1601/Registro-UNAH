<?php
    /*
    * @author Alejandro Moya 20211020462
    * @created Noviembre de 2024
    */
header("Content-Type: application/json");

$path = '../../../../';
include_once $path."src/DAO/RectificationPeriodAdmissionProcessDAO.php";

$daoRectificationPeriodAdmissionProcess = new RectificationPeriodAdmissionProcessDAO();

try {
    $isRectificationPeriodAdmissionProcessOpen = $daoRectificationPeriodAdmissionProcess->getVerifyRectificationPeriodAdmissionProcess();

    if ($isRectificationPeriodAdmissionProcessOpen) {
        echo json_encode([
            "status" => "success",
            "message" => "El proceso de rectificacion está abierto."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "El proceso de rectificacion no está disponible en este momento."
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Ocurrió un error al verificar el proceso de rectificacion: " . $e->getMessage()
    ]);
}

?>
