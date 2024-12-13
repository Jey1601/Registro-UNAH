<?php
/**
 * Endpoint para obtener la carga academica del periodo actual en formato CSV.
 * @author @AngelNolasco
 * @created 10/12/2024
 */
const PATH = '../../../../src';
include_once PATH.'/DAO/ProfessorDAO.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['idProfessor'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Numero de empleado no definido o nulo.'
    ]);
    exit;
}

$idProfessor = intval($input['idProfessor']);
$controller = new ProfessorsDAO();
$response = $controller->getReportCurrentPeriodCSV($idProfessor);

echo json_encode($response);

?>