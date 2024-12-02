<?php
/**
 * Endpoint para autenticacion de los usuarios administradores de facultad.
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

$inputBudy = json_decode(file_get_contents('php://input', true));

$userFacultyAdmin = trim($inputBudy['userFacultyAdmin']);
$passwordFacultyAdmin = trim($inputBudy['passwordFacultyAdmin']);

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