<?php
/**
 * @author @AngelNolasco
 */
const PATH = '../../../../src';
include_once PATH.'/DAO/ProfessorDAO.php';

header('Content-Type: application/json');

if (!isset($_GET['idRequest'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Numero de empleado no definido o nulo.'
    ]);
    exit;
}

$idRequest = intval($_GET['idRequest']);
$controller = new ProfessorsDAO();
$response = $controller->getDetailsRequestCancellationExceptional($idRequest);

echo json_encode($response);

?>