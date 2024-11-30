<?php
/** 
 * Endpoint para autenticacion de usuarios administradores de admisiones.
 * 
*/

include_once '../../../../src/DAO/AdmissionAdminDAO.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Metodo no permitido.'
    ]);
    exit;
}

$inputBody = json_decode(file_get_contents('php://input'), true);

$userAdmissionAdmin = trim($inputBody['userAdmissionAdmin']);
$passwordAdmissionAdmin = trim($inputBody['passwordAdmissionAdmin']);

//$regexValidationPassword = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[+*\-_])[A-Za-z\d+*\-_]{8,}$/';
// $validation = preg_match($regexValidationPassword, $passwordAdmissionAdmin);
// if (!$validation) {
//     echo json_encode([
//         'success' => false,
//         'message' => 'Credenciales invalidas.'
//     ]);
//     exit;
// }

$auth = new AdmissionAdminDAO();
$response = $auth->authAdmissionAdmin($userAdmissionAdmin, $passwordAdmissionAdmin);

echo json_encode($response);

?>