<?php

$path = '../../../../';
include_once $path . "src/DAO/Enrollment/WaitingListsClassSectionsDAO.php";  // Asegúrate de usar el DAO correcto
$dao = new WaitingListsClassSectionsDAO();  // Usar el DAO modificado

/**
 * Endpoint que recibe una solicitud POST para registrar un estudiante en la lista de espera de una sección de clase.
 * 
 * Este endpoint valida que el método de la solicitud sea POST y extrae los parámetros 
 * necesarios del cuerpo de la solicitud JSON. Luego, llama al método `insertWaitingListClassSection`
 * para registrar al estudiante en la lista de espera en la base de datos.
 * 
 * En caso de éxito, devuelve un mensaje de éxito en formato JSON.
 * Si los parámetros no son válidos o el método de solicitud no es POST, devuelve un mensaje de error.
 *
 * @api {post} http://localhost:8080/api/post/waitinglist/
 * @apiName PostWaitingListClassSection.php
 * @apiGroup WaitingList
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
 * @author Alejandro Moya 20211020462
 * @created 08/12/2024
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $_POST = json_decode($input, true);

    $student_id = $_POST['student_id'] ?? null;
    $class_section_id = $_POST['class_section_id'] ?? null;

    // Validar los parámetros recibidos
    if (
        empty($student_id) || !is_string($student_id) || strlen($student_id) !== 13 ||
        empty($class_section_id) || !is_int($class_section_id)
    ) {
        echo json_encode([
            "status" => "error",
            "message" => "Parámetros inválidos. Asegúrese de enviar un ID de estudiante válido (13 caracteres) y un ID de sección de clase válido."
        ]);
        exit;
    }

    try {
        // Llamar al método insertWaitingListClassSection para registrar al estudiante
        $result = $dao->insertWaitingListClassSection($student_id, $class_section_id);

        echo json_encode([
            "status" => $result['success'] ? "success" : "error",
            "message" => $result['message']
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Error al registrar el estudiante: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Método no permitido. Use POST."
    ]);
}
?>
