<?php

$path = '../../../../';
include_once $path . "src/DAO/Enrollment/StudentClassStatusDAO.php"; 
$dao = new StudentClassStatusDAO(); 

/**
 * Endpoint que recibe una solicitud POST para obtener las clases pendientes de aprobación de un estudiante.
 * 
 * Este endpoint valida que el método de la solicitud sea POST y extrae los parámetros 
 * necesarios del cuerpo de la solicitud JSON. Luego, llama al método `getPendingClassesByStudent`
 * para obtener las clases pendientes de aprobación para el estudiante.
 * 
 * En caso de éxito, devuelve las clases y el nombre del departamento en formato JSON.
 * Si los parámetros no son válidos o el método de solicitud no es POST, devuelve un mensaje de error.
 *
 * @api {post} http://localhost:8080/api/post/enrollment/
 * @apiName PostPendingClassesStudent.php
 * @apiGroup Enrollment
 *
 * @apiParam {string} student_id Identificador del estudiante (13 caracteres).
 *
 * @apiSuccess {String} status Estado de la operación ("success" o "error").
 * @apiSuccess {Array} data Arreglo con las clases pendientes y los departamentos correspondientes.
 *
 * @apiError (Error 400) InvalidArguments Parámetro de ID de estudiante inválido.
 * @apiError (Error 405) MethodNotAllowed El método HTTP utilizado no es permitido.
 *
 * @throws Exception Si ocurre un error en la ejecución del proceso.
 * 
 * @author Alejandro Moya 20211020462
 * @created 08/12/2024
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $_POST = json_decode($input, true);
    $student_id = $_POST['student_id'] ?? null;

    if (empty($student_id) || !is_string($student_id)) {
        echo json_encode([
            "status" => "error",
            "message" => "Parámetro inválido. Asegúrese de enviar un ID de estudiante válido."
        ]);
        exit;
    }

    try {
        $result = $dao->getPendingClassesByStudent($student_id);
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Error al obtener las clases pendientes: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Método no permitido. Use POST."
    ]);
}
?>
