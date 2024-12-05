<?php
/**
 * Endpoint para autenticacion de los usuarios estudiantes.
 */

const PATH = '../../../../src';
include_once PATH.'/DAO/StudentDAO.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Metodo no permitido.'
    ]);
    exit;
}

$inputBody = json_decode(file_get_contents('php://input'), true);

$userStudent = trim($inputBody['userStudent']);
$passwordStudent = trim($inputBody['passwordStudent']);

$regexPasswordValidation = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[+*\-_])[A-Za-z\d+*\-_]{8,}$/';
$validation = preg_match($regexPasswordValidation, $passwordStudent);
if(!$validation){
    echo json_encode([
        'success' => false,
        'message' => 'Credenciales invalidas.'
    ]);
    exit;
}

$auth = new StudentDAO();
$response = $auth->authStudent($userStudent, $passwordStudent);

echo json_encode($response);
?>