<?php
/**
 * Endpoint para autenticacion de los usuarios administradores de facultad.
 * 
 * @author @AngelNolasco
 * @created 02/12/2024
 */
const PATH = '../../../../src';
include_once PATH.'/DAO/FacultyAdminDAO.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Metodo no permitido.'
    ]);
    exit;
}

$inputBody = json_decode(file_get_contents('php://input'), true);

$userFacultyAdmin = trim($inputBody['userFacultyAdmin']);
$passwordFacultyAdmin = trim($inputBody['passwordFacultyAdmin']);

$regexPasswordValidation = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[+*\-_])[A-Za-z\d+*\-_]{8,}$/';
$validation = preg_match($regexPasswordValidation, $passwordFacultyAdmin);
if(!$validation){
    echo json_encode([
        'success' => false,
        'message' => 'Credenciales invalidas.'
    ]);
    exit;
}

$auth = new FacultyAdminDAO();
$response = $auth->authFacultyAdmin($userFacultyAdmin, $passwordFacultyAdmin);

echo json_encode($response);

?>