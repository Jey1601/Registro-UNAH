<?php
    /*
    * @author Alejandro Moya 20211020462
    * @created Noviembre de 2024
    */
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
            "message" => "Ocurrio un error en: getDataAcademicSchedulesAcademicPlanning()"
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Ocurrio un error en: getDataAcademicSchedulesAcademicPlanning(): " . $e->getMessage()
    ]);
}

?>
