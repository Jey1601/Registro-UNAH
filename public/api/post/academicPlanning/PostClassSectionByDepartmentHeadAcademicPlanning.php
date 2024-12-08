<?php

$path = '../../../../';
include_once $path . "src/DAO/ClassSectionsDAO.php";
$dao = new ClassSectionsDAO();

/**
 * Endpoint que recibe una solicitud POST para obtener las secciones de clases asociadas 
 * a un departamento y centro regional específico.
 * 
 * Este endpoint valida que el método de la solicitud sea POST y extrae los parámetros 
 * necesarios del cuerpo de la solicitud JSON. Luego, llama al método `getClassSectionByDepartmentHead` 
 * para obtener las secciones de clase basadas en los parámetros proporcionados.
 * 
 * En caso de éxito, devuelve las secciones de clases en formato JSON.
 * Si los parámetros no son válidos o el método de solicitud no es POST, devuelve un mensaje de error.
 *
 * @api {post} http://localhost:8080/api/post/academicPlanning/
 * @apiName GetClassSectionsByDepartmentHead.php
 * @apiGroup AcademicPlanning
 *
 * @apiParam {int} department_id Identificador del departamento.
 * @apiParam {int} regional_center_id Identificador del centro regional.
 *
 * @apiSuccess {String} status Estado de la operación ("success", "error").
 * @apiSuccess {Array} data Lista de las secciones de clase correspondientes.
 *
 * @apiError (Error 400) InvalidArguments Parámetros inválidos enviados en la solicitud.
 * @apiError (Error 405) MethodNotAllowed El método HTTP utilizado no es permitido.
 *
 * @throws Exception Si ocurre un error en la ejecución del proceso.
 * @author Alejandro Moya 20211020462
 * @created 07/12/2024
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener la entrada JSON
    $input = file_get_contents('php://input');
    $_POST = json_decode($input, true);
    $department_id = $_POST['department_id'];
    $regional_center_id = $_POST['regional_center_id'];
    if (!is_int($department_id) || !is_int($regional_center_id)) {
        echo json_encode(["error" => "Parámetros inválidos. Asegúrese de enviar enteros para los IDs."]);
        exit;
    }
    try {
        $result = $dao->getClassSectionByDepartmentHead($department_id, $regional_center_id);
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Error en la ejecución de getClassSectionByDepartmentHead(): " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Método no permitido. Use POST."]);
}
?>
