<?php


$path = '../../../../';
include_once $path."src/DAO/DataClassesAcademicPlanningDAO.php";
$dao = new DataClassesAcademicPlanningDAO();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = file_get_contents('php://input');
    $_POST = json_decode($input, true);
    $idUndergraduate = $_POST['idUndergraduate'];
    $academicPeriodicity = $_POST['academicPeriodicity'];
    $result = $dao->getClassesAcademicPlanningByUndergraduate($idUndergraduate, $academicPeriodicity);
    echo json_encode($result);
} else {
    echo json_encode(["error" => "Método no permitido. Use POST."]);
}

?>