<?php
require 'ResetStudents.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';

        if (empty($email)) {
            throw new Exception("El correo es requerido.");
        }

        $reset = new ResetStudents();
        $connection = $reset->getConnection();

        $success = $reset->requestPasswordReset($connection, $email);

        if ($success) {
            $response['success'] = true;
            $response['message'] = "Se ha enviado un enlace de restablecimiento a tu correo.";
        } else {
            $response['message'] = "No se pudo procesar tu solicitud. Verifica tu correo.";
        }
    } else {
        throw new Exception("Método no permitido.");
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
