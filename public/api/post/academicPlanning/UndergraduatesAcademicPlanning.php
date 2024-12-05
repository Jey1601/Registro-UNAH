<?php


$path = '../../../../';
include_once $path."src/DAO/UndergraduatesAcademicPlanningDAO.php";
$dao = new UndergraduatesAcademicPlanningDAO();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = file_get_contents('php://input');
    $_POST = json_decode($input, true);
    $username_user_professor = $_POST['username_user_professor'];
    $regionalCenter = $_POST['id_regionalcenter'];
    $result = $dao->getUndergraduatesByRegionalCentersAndDepartmentHead($regionalCenter, $username_user_professor);
    echo json_encode($result);
} else {
    echo json_encode(["error" => "Método no permitido. Use POST."]);
}

?>