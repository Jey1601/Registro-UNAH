<?php
/**
 * @author @AngelNolasco
 * @created 10/12/2024
 */
const PATH = '../../../../src';
include_once PATH.'/DAO/ProfessorDAO.php';

header('Content-Type: application/json');

if (!isset($_POST['idProfessor'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Numero de empleado no definido o nulo.'
    ]);
    exit;
}

$idProfessor = $_POST['idProfessor'];
$controller = new ProfessorsDAO();
$response = $controller->getReportCurrentPeriod($idProfessor);

echo json_encode($response);

?>