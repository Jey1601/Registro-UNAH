<?php
/**
 * @author @AngelNolasco
 * @created 11/12/2024
 */
const PATH = '../../../../src';
include_once PATH.'/DAO/ProfessorDAO.php';

header('Content-Type: application/json');

if (!isset($_POST['idProfessor'], $_POST['idStudent'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Parametros no definidos o nulos.'
    ]);
    exit;
}

$idProfessor = $_POST['idProfessor'];
$idStudent = $_POST['idStudent'];
$controller = new ProfessorsDAO();
$response = $controller->getAcademicHistoryByStudent($idProfessor, $idStudent);

echo json_encode($response);

?>