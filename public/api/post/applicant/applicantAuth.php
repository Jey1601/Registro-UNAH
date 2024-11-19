<?php
include_once '../Registro-UNAH-ladingpage/src/applicant/applicant.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'httpCode' => http_response_code(405),
        'message' => 'Method Not Allowed'
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
if (!preg_match($regexValidationID, $numID)) {
    echo json_encode([
        'httpCode' => http_response_code(401),
        'message' => 'Invalid credentials.'
    ]);
    exit;
}
$numRequest = intval($numRequest);

$auth = new ApplicantDAO();
$response = $auth->validateApplicant($numID, $numRequest);

echo $response;
?>