<?php

$path = '../../../../';
include_once $path . "src/DAO/ClassSectionsDAO.php"; 
$dao = new ClassSectionsDAO(); 

/**
 * Endpoint que recibe una solicitud POST para obtener las secciones activas de una clase para un estudiante.
 * 
 * Este endpoint valida que el método de la solicitud sea POST y extrae los parámetros 
 * necesarios del cuerpo de la solicitud JSON. Luego, llama al método `getClassSectionsForStudent`
 * para obtener las secciones activas de la clase correspondiente al estudiante.
 * 
 * En caso de éxito, devuelve las secciones y el mensaje de éxito en formato JSON.
 * Si los parámetros no son válidos o el método de solicitud no es POST, devuelve un mensaje de error.
 *
 * @api {post} http://localhost:8080/api/post/enrollment/
 * @apiName PostClassSectionsForStudent.php
 * @apiGroup Enrollment
 *
 * @apiParam {string} student_id Identificador del estudiante (13 caracteres).
 * @apiParam {int} class_id Identificador de la clase.
 *
 * @apiSuccess {String} status Estado de la operación ("success" o "error").
 * @apiSuccess {Array} data Arreglo con las secciones de la clase para el estudiante.
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
    $class_id = $_POST['class_id'] ?? null;

    if (empty($student_id) || !is_string($student_id) || empty($class_id) || !is_int($class_id)) {
        echo json_encode([
            "status" => "error",
            "message" => "Parámetros inválidos. Asegúrese de enviar un ID de estudiante válido y un ID de clase válido."
        ]);
        exit;
    }

    try {
        $result = $dao->getClassSectionsForStudent($student_id, $class_id);
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Error al obtener las secciones de clases: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Método no permitido. Use POST."
    ]);
}
?>
