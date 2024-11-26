<?php
/**
 * Endpoint para la autenticación de usuarios de aspirantes.
 */

include_once '../../../../src/DAO/ApplicantDAO.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Metodo no permitido.'
    ]);
    exit;
}

//Decodificacion del body
$inputBody = json_decode(file_get_contents('php://input'), true);

//Captura y limpieza de datos
$numID = trim($inputBody['numID'] ?? '');
$numRequest = trim($inputBody['numRequest'] ?? '');

//Validacion de numero de identidad
$regexValidationID = '/(0|1)[1-8][0-2][0-8](1|2)(0|9)\d{7}$/';
$validation = preg_match($regexValidationID, $numID);
if (!$validation) {
    echo json_encode([
        'success' => false,
        'message' => 'Credenciales invalidas.'
    ]);
    exit;
}
$numRequest = intval($numRequest);

$auth = new ApplicantDAO();
$response = $auth->authApplicant($numID, $numRequest);

echo json_encode($response);
?>