<?php


$path = '../../../../';
include_once $path."src/DAO/RegionalCentersAcademicPlanningDAO.php";
$dao = new RegionalCentersAcademicPlanningDAO();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = file_get_contents('php://input');
    $_POST = json_decode($input, true); 
    $username_user_professor = $_POST['username_user_professor'];
    $result = $dao->getRegionalCentersByDepartmentHead($username_user_professor);
    echo json_encode($result);
} else {
    echo json_encode(["error" => "Método no permitido. Use POST."]);
}

?>