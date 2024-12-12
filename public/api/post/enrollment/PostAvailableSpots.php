<?php

$path = '../../../../';
include_once $path . "src/DAO/ClassSectionsDAO.php"; 
$dao = new ClassSectionsDAO(); 

/**
 * Endpoint que recibe una solicitud POST para obtener los espacios disponibles en una sección de clase.
 * 
 * Este endpoint valida que el método de la solicitud sea POST y extrae el parámetro 
 * necesario del cuerpo de la solicitud JSON. Luego, llama al método `getAvailableSpots`
 * para obtener el número de espacios disponibles en la sección de clase especificada.
 * 
 * En caso de éxito, devuelve el número de espacios disponibles en formato JSON.
 * Si los parámetros no son válidos o el método de solicitud no es POST, devuelve un mensaje de error.
 *
 * @api {post} http://localhost:8080/api/post/enrollment/
 * @apiName PostAvailableSpots.php
 * @apiGroup Enrollment
 *
 * @apiParam {int} class_section_id ID de la sección de clase.
 *
 * @apiSuccess {String} status Estado de la operación ("success" o "error").
 * @apiSuccess {int|null} available_spots Número de espacios disponibles (o null si no se encuentra la sección).
 *
 * @apiError (Error 400) InvalidArguments Parámetro de ID de sección de clase inválido.
 * @apiError (Error 405) MethodNotAllowed El método HTTP utilizado no es permitido.
 *
 * @throws Exception Si ocurre un error en la ejecución del proceso.
 * 
 * @author Alejandro Moya 20211020462
 * @created 08/12/2024
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener entrada JSON
    $input = file_get_contents('php://input');
    $_POST = json_decode($input, true);
    $class_section_id = $_POST['class_section_id'] ?? null;

    // Validar parámetros de entrada
    if (empty($class_section_id) || !is_int($class_section_id)) {
        echo json_encode([
            "status" => "error",
            "message" => "Parámetro inválido. Asegúrese de enviar un ID de sección de clase válido (entero)."
        ]);
        exit;
    }

    try {
        // Llamar al método para obtener los espacios disponibles
        $result = $dao->getAvailableSpots($class_section_id);

        // Responder con el resultado
        echo json_encode([
            "status" => $result['success'] ? "success" : "error",
            "available_spots" => $result['availableSpots'],
            "message" => $result['message']
        ]);
    } catch (Exception $e) {
        // Manejo de errores
        echo json_encode([
            "status" => "error",
            "message" => "Error al obtener los espacios disponibles: " . $e->getMessage()
        ]);
    }
} else {
    // Respuesta para métodos no permitidos
    echo json_encode([
        "status" => "error",
        "message" => "Método no permitido. Use POST."
    ]);
}
?>
