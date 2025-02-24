<?php
/**
 * @author @AngelNolasco
 * @created 11/12/2024
 */
const PATH = '../../../../src';
include_once PATH.'/DAO/ProfessorDAO.php';

header('Content-Type: application/json');

if (!isset($_POST['idProfessor'], $_POST['idRegionalCenter'], $_POST['idUndergraduate'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Parametros no definidos o nulos.'
    ]);
    exit;
}

$idProfessor = intval($_POST['idProfessor']);
$idRegionalCenter = intval($_POST['idRegionalCenter']);
$idUndergraduate = intval($_POST['idUndergraduate']);

$controller = new ProfessorsDAO();
$response = $controller->getStudentsByRegionalCenterUndergraduate($idProfessor, $idRegionalCenter, $idUndergraduate);

echo json_encode($response);

?>