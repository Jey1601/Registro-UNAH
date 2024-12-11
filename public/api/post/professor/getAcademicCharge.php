<?php
/**
 * @author @AngelNolasco
 * @created 10/12/2024
 */
const PATH = '../../../../src';
include_once PATH.'/DAO/ProfessorDAO.php';

header('Content-Type: application/json');


$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['idProfessor'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Número de empleado no definido o nulo.'
    ]);
    exit;
}

$idProfessor = intval($data['idProfessor']);
$controller = new ProfessorsDAO();
$response = $controller->getReportCurrentPeriod($idProfessor);

echo json_encode($response);
?>