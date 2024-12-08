<?php

$path = '../../../../';
include_once $path . "src/DAO/ClassSectionsDaysDAO.php";
$dao = new  ClassSectionsDaysDAO();

/**
 * Endpoint que recibe una solicitud POST para asignar días a una sección de clase.
 * 
 * Este endpoint valida que el método de la solicitud sea POST y extrae los parámetros 
 * necesarios del cuerpo de la solicitud JSON. Luego, llama al método `createClassSectionsDays` 
 * para asignar los días a la sección de clase correspondiente.
 * 
 * En caso de éxito, devuelve un mensaje de confirmación en formato JSON.
 * Si los parámetros no son válidos o el método de solicitud no es POST, devuelve un mensaje de error.
 *
 * @api {post} http://localhost:8080/api/post/academicPlanning/
 * @apiName PostClassSectionsDaysAcademicPlanning.php
 * @apiGroup AcademicPlanning
 *
 * @apiParam {int} newClassSectionId Identificador de la nueva sección de clase.
 * @apiParam {Array} days Array de días a asignar (por ejemplo, ["Lunes", "Miercoles", "Viernes"]).
 *
 * @apiSuccess {String} status Estado de la operación ("success", "error").
 * @apiSuccess {String} message Mensaje de éxito o error.
 *
 * @apiError (Error 400) InvalidArguments Parámetros inválidos enviados en la solicitud.
 * @apiError (Error 405) MethodNotAllowed El método HTTP utilizado no es permitido.
 *
 * @throws Exception Si ocurre un error en la ejecución del proceso.
 * 
 * @author Alejandro Moya
 * @created 07/12/2024
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener la entrada JSON
    $input = file_get_contents('php://input');
    $_POST = json_decode($input, true);

    // Validar parámetros
    $newClassSectionId = $_POST['newClassSectionId'] ?? null;
    $days = $_POST['days'] ?? null;

    if (!is_int($newClassSectionId) || !is_array($days) || empty($days)) {
        echo json_encode([
            "status" => "error",
            "message" => "Parámetros inválidos. Asegúrese de enviar un ID entero y un array no vacío de días."
        ]);
        exit;
    }

    try {
        // Llamar al método del DAO
        $result = $dao->createClassSectionsDays($newClassSectionId, $days);
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Error en la ejecución de createClassSectionsDays(): " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Método no permitido. Use POST."
    ]);
}
?>
