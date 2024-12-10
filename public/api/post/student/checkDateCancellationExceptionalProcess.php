<?php
const PATH = '../../../../src';
include_once PATH.'/DAO/StudentDAO.php';

header('Content-Type: application/json');
if (!isset($_POST['idStudent'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Faltan datos requeridos en la solicitud.'
    ]);
    exit;
}

$idStudent = $_POST['idStudent'];
$controller = new StudentDAO();
$response = $controller->checkDatesCancellationExceptionalProcess($idStudent);

echo $response;
?>