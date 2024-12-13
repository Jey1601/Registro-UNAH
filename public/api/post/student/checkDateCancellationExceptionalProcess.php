<?php
/**
 * Endpoint para realizar la verificacion de fecha de realizacion de solicitud de cancelacion excepcional de clases.
 * @author @AngelNolasco
 * @created 10/12/2024
 */
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