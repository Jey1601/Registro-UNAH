<?php

$path = '../../../../';
include_once $path . "src/DAO/Enrollment/EnrollmentClassSectionsDAO.php"; 
$dao = new EnrollmentClassSectionsDAO(); 

/**
 * Endpoint que recibe una solicitud POST para obtener el número de estudiantes matriculados en una sección de clase.
 * 
 * Este endpoint valida que el método de la solicitud sea POST y extrae los parámetros 
 * necesarios del cuerpo de la solicitud JSON. Luego, llama al método `getStudentCountByClassSection`
 * para obtener el número de estudiantes matriculados en la sección de clase.
 * 
 * En caso de éxito, devuelve el número de estudiantes en formato JSON.
 * Si los parámetros no son válidos o el método de solicitud no es POST, devuelve un mensaje de error.
 *
 * @api {post} http://localhost:8080/api/post/enrollment/
 * @apiName PostStudentCount.php
 * @apiGroup Enrollment
 *
 * @apiParam {int} class_section_id Identificador de la sección de clase.
 *
 * @apiSuccess {String} status Estado de la operación ("success" o "error").
 * @apiSuccess {int} student_count Número de estudiantes matriculados en la sección de clase.
 *
 * @apiError (Error 400) InvalidArguments Parámetros inválidos.
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

    $class_section_id = $_POST['class_section_id'] ?? null;

    if (empty($class_section_id) || !is_int($class_section_id)) {
        echo json_encode([
            "status" => "error",
            "message" => "Parámetros inválidos. Asegúrese de enviar un ID de sección de clase válido."
        ]);
        exit;
    }

    try {
        $result = $dao->getStudentCountByClassSection($class_section_id);

        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Error al obtener el número de estudiantes: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Método no permitido. Use POST."
    ]);
}
?>
