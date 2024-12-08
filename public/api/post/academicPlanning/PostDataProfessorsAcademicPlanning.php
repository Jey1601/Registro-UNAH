<?php


$path = '../../../../';
include_once $path."src/DAO/DataProfessorsAcademicPlanningDAO.php";
$dao = new DataProfessorsAcademicPlanningDAO();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = file_get_contents('php://input');
    $_POST = json_decode($input, true);
    $regionalCenter = $_POST['id_regionalcenter'];
    $username_user_professor = $_POST['username_user_professor'];
    $days = $_POST['days'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $result = $dao->getDataProfessorsAcademicPlanning($regionalCenter, $username_user_professor, $days, $startTime, $endTime);
    echo json_encode($result);
} else {
    echo json_encode(["error" => "Método no permitido. Use POST."]);
}

?>