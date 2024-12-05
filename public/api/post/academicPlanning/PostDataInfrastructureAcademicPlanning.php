<?php


$path = '../../../../';
include_once $path."src/DAO/DataInfrastructureAcademicPlanningDAO.php";
$dao = new DataInfrastructureAcademicPlanningDAO();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = file_get_contents('php://input');
    $_POST = json_decode($input, true);
    $regionalCenter = $_POST['id_regionalcenter'];
    $username_user_professor = $_POST['username_user_professor'];
    $result = $dao->getDataInfrastructureAcademicPlanning($regionalCenter, $username_user_professor);
    echo json_encode($result);
} else {
    echo json_encode(["error" => "Método no permitido. Use POST."]);
}

?>