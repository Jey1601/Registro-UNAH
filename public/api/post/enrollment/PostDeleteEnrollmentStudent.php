<?php

$path = '../../../../';
include_once $path . "src/DAO/enrollment/EnrollmentClassSectionsDAO.php"; 
$dao = new EnrollmentClassSectionsDAO(); 

/**
 * Endpoint que recibe una solicitud POST para actualizar el estado de inscripcion de un estudiante en una sección de clase.
 * 
 * Este endpoint valida que el método de la solicitud sea POST y extrae los parámetros 
 * necesarios del cuerpo de la solicitud JSON. Luego, llama al método `updateEnrollmentStatus`
 * para actualizar el estado en la base de datos.
 * 
 * En caso de éxito, devuelve un mensaje de éxito en formato JSON.
 * Si los parámetros no son válidos o el método de solicitud no es POST, devuelve un mensaje de error.
 *
 * @api {post} http://localhost:8080/api/post/enrollment/
 * @apiName PostDeleteEnrollmentStudent.php
 * @apiGroup Enrollment
 *
 * @apiParam {string} student_id Identificador del estudiante (13 caracteres).
 * @apiParam {int} class_section_id Identificador de la sección de clase.
 *
 * @apiSuccess {String} status Estado de la operación ("success" o "error").
 * @apiSuccess {String} message Mensaje de éxito o error.
 *
 * @apiError (Error 400) InvalidArguments Parámetros inválidos.
 * @apiError (Error 405) MethodNotAllowed El método HTTP utilizado no es permitido.
 *
 * @throws Exception Si ocurre un error en la ejecución del proceso.
 * 
 * @author Alejandro Moya
 * @created 09/12/2024
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $_POST = json_decode($input, true);

    $student_id = $_POST['student_id'] ?? null;
    $class_section_id = $_POST['class_section_id'] ?? null;

    if (
        empty($student_id) || !is_string($student_id) || strlen($student_id) > 13 ||
        empty($class_section_id) || !is_int($class_section_id)
    ) {
        echo json_encode([
            "status" => "error",
            "message" => "Parámetros inválidos. Asegúrese de enviar un ID de estudiante válido (13 caracteres) y un ID de sección de clase valido."
        ]);
        exit;
    }

    try {
        $result = $dao->updateEnrollmentStatus($student_id, $class_section_id);

        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Error al actualizar el estado de inscripcion: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Método no permitido. Use POST."
    ]);
}
