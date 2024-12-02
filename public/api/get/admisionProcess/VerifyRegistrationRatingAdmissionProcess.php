<?php

header("Content-Type: application/json");

$path = '../../../../';
include_once $path."src/DAO/RegistrationRatingAdmissionProcessDAO.php";

$daoRegistrationRatingAdmissionProcess = new RegistrationRatingAdmissionProcessDAO();

try {
    $isRegistrationRatingAdmissionProcessOpen = $daoRegistrationRatingAdmissionProcess->getVerifyRegistrationRatingAdmissionProcess();

    if ($isRegistrationRatingAdmissionProcessOpen) {
        echo json_encode([
            "status" => "success",
            "message" => "El proceso de registro de calificaciones de examenes de admision está abierto."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "El proceso de registro de calificaciones de examenes de admision no está disponible en este momento."
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Ocurrió un error al verificar el proceso de registro de calificaciones de examenes de admision: " . $e->getMessage()
    ]);
}

?>
