<?php


header("Content-Type: application/json");

include_once "../../../../src/DAO/ApplicantDAO.php";

$dao = new ApplicantDAO("localhost", "root", "your_password", "unah_registration");

$applicationsData = $dao->viewData();

if ($applicationsData === null) {
    echo json_encode(["error" => "Error en la consulta o datos no encontrados"]);
} else {
    echo json_encode($applicationsData);
}
?>