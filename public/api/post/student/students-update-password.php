<?php
require '../../../../src/DAO/util/ResetStudents.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['token'] ?? null;
        $newPassword = $_POST['new_password'] ?? null;

        if (empty($token) || empty($newPassword)) {
            throw new Exception("Token y nueva contraseña son requeridos.");
        }

        $reset = new ResetStudents();
        $connection = $reset->getConnection();

        $reset->validateTokenAndProceed($token, 'student', $connection);

        $reset->resetPasswordWithTokenValidation($token, $newPassword, $connection);

        $response['success'] = true;
        $response['message'] = "Contraseña actualizada exitosamente.";
    } else {
        throw new Exception("Método no permitido.");
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

echo json_encode($response);
