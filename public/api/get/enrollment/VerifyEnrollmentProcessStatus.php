<?php

$path = '../../../../';
include_once $path . "src/DAO/Enrollment/EnrollmentProcessDAO.php"; 
$dao = new EnrollmentProcessDAO(); 

/**
 * Endpoint que verifica si existe un proceso de matrícula activo.
 * 
 * Este endpoint responde a una solicitud GET para llamar al método 
 * `checkEnrollmentProcessStatus` y verificar la existencia de un proceso de matrícula activo.
 * 
 * En caso de éxito, devuelve el estado del proceso en formato JSON.
 * Si el método de solicitud no es GET, devuelve un mensaje de error.
 *
 * @api {get} http://localhost:8080/api/get/enrollment/
 * @apiName VerifyEnrollmentProcessStatus.php
 * @apiGroup Enrollment
 *
 * @apiSuccess {String} status Estado de la operación ("success", "not_found" o "error").
 * @apiSuccess {Boolean|null} process_exists Indica si existe un proceso de matrícula activo (o null en caso de error).
 * @apiError (Error 405) MethodNotAllowed El método HTTP utilizado no es permitido.
 *
 * @throws Exception Si ocurre un error en la ejecución del proceso.
 * 
 * @author Alejandro Moya 20211020462
 * @created 09/12/2024
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $result = $dao->checkEnrollmentProcessStatus();
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Error al verificar el estado del proceso de matrícula: " . $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Método no permitido. Use GET."
    ]);
}
?>
