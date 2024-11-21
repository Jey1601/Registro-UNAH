<?php
include_once '../../../src/DAO/util/jwt.php';

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
    $response = JWT::validateToken($token);

    if ($response === false) {
        echo json_encode([
            'success' => false,
            'message' => 'Token invalido.'
        ]);
        exit;
    } else {
        echo json_encode([
            'success' => true,
            'message' => 'Validacion exitosa.',
            'payload' => $response
        ]);
    }
}

?>