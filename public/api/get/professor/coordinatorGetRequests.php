<?php
const PATH = '../../../../src';
include_once PATH.'/DAO/ProfessorDAO.php';

if (!(isset($_POST['idProfessor']))) {
    echo json_encode([
        'success' => false,
        'message' => 'Numero de empleado de docente no definido o nulo.'
    ]);
    exit;
}

$idProfessor = $_POST['idProfessor'];
$controller = new ProfessorsDAO();
$response = $controller->getRequests($idProfessor);

echo json_encode($response);

?>