<?php
/**
 * Endpoint para la autenticación de usuarios de aspirantes.
 * @author @AngelNolasco
 */

 $path = '../../../../';
 include_once $path."src/DAO/ApplicantDAO.php";

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
$username = trim($inputBody['username'] ?? '');
$password = trim($inputBody['password'] ?? '');

//Validacion de numero de identidad
$regexValidationID = '/(0|1)[1-8][0-2][0-8](1|2)(0|9)\d{7}$/';
$validation = preg_match($regexValidationID, $username);
if (!$validation) {
    echo json_encode([
        'success' => false,
        'message' => 'Credenciales invalidas.'
    ]);
    exit;
}

$auth = new ApplicantDAO();
$response = $auth->authApplicant($username, $password);

echo json_encode($response);
?>