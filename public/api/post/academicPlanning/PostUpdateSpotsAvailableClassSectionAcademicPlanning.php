<?php

$path = '../../../../';
include_once $path . "src/DAO/ClassSectionsDAO.php";
$dao = new ClassSectionsDAO();

/**
 * Endpoint que recibe una solicitud POST para actualizar el número de spots disponibles 
 * en una sección de clase específica.
 * 
 * Este endpoint valida que el método de la solicitud sea POST y extrae los parámetros 
 * necesarios del cuerpo de la solicitud JSON. Luego, llama al método `updateSpotsAvailableClassSection`
 * para actualizar el número de spots en la sección de clase indicada.
 * 
 * En caso de éxito, devuelve un mensaje de éxito en formato JSON.
 * Si los parámetros no son válidos o el método de solicitud no es POST, devuelve un mensaje de error.
 *
 * @api {post} http://localhost:8080/api/post/academicPlanning/
 * @apiName PostUpdateSpotsAvailableClassSectionAcademicPlanning.php
 * @apiGroup AcademicPlanning
 *
 * @apiParam {int} id_class_section Identificador de la sección de clase a actualizar.
 * @apiParam {int} new_spots_number Nuevo número de spots disponibles para la sección de clase.
 *
 * @apiSuccess {String} status Estado de la operación ("success", "error").
 * @apiSuccess {String} message Mensaje de éxito o error.
 *
 * @apiError (Error 400) InvalidArguments Parámetros inválidos enviados en la solicitud.
 * @apiError (Error 405) MethodNotAllowed El método HTTP utilizado no es permitido.
 *
 * @throws Exception Si ocurre un error en la ejecución del proceso.
 * @author Alejandro Moya 20211020462
 * @created 07/12/2024
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = file_get_contents('php://input');
    $_POST = json_decode($input, true);
    $id_class_section = $_POST['id_class_section'];
    $new_spots_number = $_POST['new_spots_number'];
    if (!is_int($id_class_section) || !is_int($new_spots_number)) {
        echo json_encode(["error" => "Parámetros inválidos. Asegúrese de enviar enteros para los IDs y el número de spots."]);
        exit;
    }
    try {
        $result = $dao->updateSpotsAvailableClassSection($id_class_section, $new_spots_number);
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Error al actualizar los spots: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Método no permitido. Use POST."]);
}
?>
