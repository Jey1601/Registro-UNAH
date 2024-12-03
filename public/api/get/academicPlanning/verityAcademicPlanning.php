<?php

header("Content-Type: application/json");

$path = '../../../../';
include_once $path."src/DAO/AcademicPlanningStatusDAO.php";

$daoAcademicPlanning = new AcademicPlanningStatusDAO();

try {
    $isAcademicPlanningOpen = $daoAcademicPlanning->getVerifyAcademicPlanning();

    if ($isAcademicPlanningOpen) {
        echo json_encode([
            "status" => "success",
            "message" => "El proceso de planificacion está abierto."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "El proceso de planificacion no está disponible en este momento."
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Ocurrió un error al verificar el proceso de planificacion academica: " . $e->getMessage()
    ]);
}

?>
