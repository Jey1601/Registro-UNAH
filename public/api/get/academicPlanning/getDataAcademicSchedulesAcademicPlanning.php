<?php

header("Content-Type: application/json");

$path = '../../../../';
include_once $path . "src/DAO/DataAcademicSchedulesAcademicPlanningDAO.php";

$daoDataAcademicSchedulesAcademicPlanning = new DataAcademicSchedulesAcademicPlanningDAO();

try {
    $result = $daoDataAcademicSchedulesAcademicPlanning->getDataAcademicSchedulesAcademicPlanning();

    if ($result['status'] === 'success') {
        echo json_encode([
            "status" => "success",
            "data" => $result['data'] 
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "El proceso de planificación no está disponible en este momento."
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Ocurrió un error al verificar el proceso de planificación académica: " . $e->getMessage()
    ]);
}

?>
