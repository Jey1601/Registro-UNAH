<?php
/**
 * @author @AngelNolasco
 * @created 10/12/2024
 */

const PATH = '../../../../src';
include_once PATH.'/DAO/ProfessorDAO.php';

header('Content-Type: application/json');

if (!isset($_GET['idSectionClass'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Codigo de seccion no definido o nulo.'
    ]);
    exit;
}

$idSectionClass = $_GET['idSectionClass'];
$controller = new ProfessorsDAO();
$response = $controller->getStudentsBySection($idSectionClass);

echo json_encode($response);

?>