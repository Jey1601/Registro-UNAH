<?php
/**
 * Endpoint para la validación del token de la sesión. Precarga a páginas HTML.
 */
include_once '../../../../../src/DAO/util/tokenVerification.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['token']) || empty($data['token'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Token nulo.'
    ]);
    exit;
} else {
    $token = $data['token'];
    $typeUser = $data['typeUser'];
    $tokenVerification = new TokenVerification();
    $response = $tokenVerification->tokenVerification($token, $typeUser);

    if ($response['success'] === false) {
        echo json_encode([
            'success' => false,
            'message' => 'Token invalido.'
        ]);
        exit;
    } else {
        echo json_encode([
            'success' => true,
            'message' => 'Validacion exitosa.',
            'tokenExpiration' => $response['tokenExpiration']
        ]);
    }
}
?>