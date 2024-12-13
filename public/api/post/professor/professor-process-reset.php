<?php
/**
* Script para gestionar la solicitud de restablecimiento de contraseña.
*
* Este script recibe una solicitud POST con un correo electrónico y utiliza la clase
* ResetProfessors para procesar la solicitud de restablecimiento de contraseña.
*
* @author Kenia Romero
* @created 09/11/2024
*/
require 'ResetProfessors.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
     /**
     * Verifica si la solicitud es de tipo POST.
     *
     * @throws Exception Si el método no es POST.
     */
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
         /**
         * Verifica que el correo no esté vacío.
         *
         * @throws Exception Si el correo es requerido.
         */
        if (empty($email)) {
            throw new Exception("El correo es requerido.");
        }

        $reset = new ResetProfessor();
        $connection = $reset->getConnection();
         /**
         * Solicita el restablecimiento de contraseña.
         *
         * @param mysqli $connection Conexión activa a la base de datos.
         * @param string $email Correo electrónico del usuario.
         *
         * @return bool Indica si la solicitud fue exitosa.
         */
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
/**
* Devuelve la respuesta en formato JSON.
*
* @return string Respuesta codificada en JSON.
*/
echo json_encode($response);
