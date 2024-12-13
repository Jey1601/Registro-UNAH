<?php
/**
 * Endpoint para autenticacion de los usuarios administradores de registro.
 * @author @AngelNolasco
 */
const PATH = '../../../../src';
include_once PATH.'/DAO/DIIPAdminDAO.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Metodo no permitido.'
    ]);
    exit;
}

$inputBody = json_decode(file_get_contents('php://input'), true);

$userDIIPAdmin = trim($inputBody['userDIIPAdmin']);
$passwordDIIPAdmin = trim($inputBody['passwordDIIPAdmin']);

$regexPasswordValidation = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[+*\-_])[A-Za-z\d+*\-_]{8,}$/';
$validation = preg_match($regexPasswordValidation, $passwordDIIPAdmin);
if(!$validation){
    echo json_encode([
        'success' => false,
        'message' => 'Credenciales invalidas.'
    ]);
    exit;
}

$auth = new DIIPAdminDAO();
$response = $auth->authDIIPAdmin($userDIIPAdmin, $passwordDIIPAdmin);

echo json_encode($response);

?>