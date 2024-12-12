<?php
/**
 * @author @AngelNolasco
 * @created 10/12/2024
 */

const PATH = '../../../../src';
include_once PATH.'/DAO/ProfessorDAO.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Metodo no permitido.'
    ]);
    exit;
}
if (!isset($_POST['idProfessor'], $_POST['startDateString'], $_POST['endDateString'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Parametros no definidos o nulos.'
    ]);
    exit;
}

$idProfessor = $_POST['idProfessor']; 
$startDateString = $_POST['startDateString']; //Se esperan fechas
$endDateString = $_POST['endDateString'];
$controller = new ProfessorsDAO();
$response = $controller->setDateCancellationExceptionalProcess($idProfessor, $startDateString, $endDateString);

echo json_encode($response);

?>