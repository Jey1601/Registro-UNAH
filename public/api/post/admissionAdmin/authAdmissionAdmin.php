<?php
/** 
 * Endpoint para autenticacion de Administrador de admisiones
 * 
*/

include_once '../Registro-UNAH/src/DAO/admissionAdminDAO.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Method Not Allowed'
    ]);
    exit;
}

$inputBody = json_decode(file_get_contents('php://input'), true);

$userAdmissionAdmin = trim($inputBody['userAdmissionAdmin']);
$passwordAdmissionAdmin = trim($inputBody['passwordAdmissionAdmin']);

$regexValidationPassword = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[+*\-_])[A-Za-z\d+*\-_]{8,}$/';
if (!preg_match($regexValidationPassword, $passwordAdmissionAdmin)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid credentials.'
    ]);
    exit;
}

$auth = new AdmissionAdminDAO();
$response = $auth->authAdmissionAdmin($userAdmissionAdmin, $passwordAdmissionAdmin);

echo json_encode($response);

?>