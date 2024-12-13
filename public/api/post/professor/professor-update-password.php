<?php
/**
* Script para gestionar la actualización de la contraseña de un estudiante.
*
* Este script recibe una solicitud POST con un token y una nueva contraseña,
* y utiliza la clase ResetStudents para validar el token y actualizar la contraseña.
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
        $token = $_POST['token'] ?? null;
        $newPassword = $_POST['new_password'] ?? null;
        /**
        * Verifica que el token y la nueva contraseña no estén vacíos.
        *
        * @throws Exception Si el token o la nueva contraseña son requeridos.
        */
        if (empty($token) || empty($newPassword)) {
            throw new Exception("Token y nueva contraseña son requeridos.");
        }

        $reset = new ResetProfessor();
        $connection = $reset->getConnection();
        /**
        * Valida el token y procede con el restablecimiento de contraseña.
        *
        * @param string $token Token a validar.
        * @param string $typeUser Tipo de usuario (en este caso, 'student').
        * @param mysqli $connection Conexión activa a la base de datos.
        *
        * @throws Exception Si el token es inválido o ha expirado.
        */
        $reset->validateTokenAndProceed($token, 'professor', $connection);
        /**
        * Restablece la contraseña del usuario después de validar el token.
        *
        * @param string $token Token de restablecimiento.
        * @param string $newPassword Nueva contraseña.
        * @param mysqli $connection Conexión activa a la base de datos.
        *
        * @return bool Indica si el restablecimiento fue exitoso.
        *
        * @throws Exception Si no se pudo actualizar la contraseña.
        */
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
/**
* Devuelve la respuesta en formato JSON.
*
* @return string Respuesta codificada en JSON.
*/
echo json_encode($response);
