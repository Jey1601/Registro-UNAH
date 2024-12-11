<?php

$path = '../../../../';
include_once $path . "src/DAO/ClassSectionsDAO.php";
$dao = new ClassSectionsDAO();

/**
 * Endpoint que recibe una solicitud POST para eliminar una sección de clase.
 * 
 * Este endpoint valida que el método de la solicitud sea POST y extrae los parámetros 
 * necesarios del cuerpo de la solicitud JSON. Luego, llama al método `deleteClassSectionsByDepartmentHead` 
 * para realizar la eliminación de la sección de clase.
 * 
 * En caso de éxito, devuelve un mensaje de confirmación en formato JSON.
 * Si los parámetros no son válidos o el método de solicitud no es POST, devuelve un mensaje de error.
 *
 * @api {post} http://localhost:8080/api/post/academicPlanning/
 * @apiName PostdeleteClassSectionsByDepartmentHead.php
 * @apiGroup AcademicPlanning
 *
 * @apiParam {int} idClassSection Identificador de la sección de clase a eliminar.
 * @apiParam {int} department_id Identificador del departamento responsable.
 * @apiParam {string} justification Justificación para eliminar la sección.
 *
 * @apiSuccess {String} status Estado de la operación ("success", "error", "warning").
 * @apiSuccess {String} message Mensaje de éxito, advertencia o error.
 *
 * @apiError (Error 400) InvalidArguments Parámetros inválidos enviados en la solicitud.
 * @apiError (Error 405) MethodNotAllowed El método HTTP utilizado no es permitido.
 *
 * @throws Exception Si ocurre un error en la ejecución del proceso.
 * 
 * @author Alejandro Moya 20211020462
 * @created 07/12/2024
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener la entrada JSON
    $input = file_get_contents('php://input');
    $_POST = json_decode($input, true);

    // Validar parámetros
    $idClassSection = $_POST['idClassSection'] ?? null;
    $department_id = $_POST['department_id'] ?? null;
    $justification = $_POST['justification'] ?? null;
    $usernameProfessor = $_POST['usernameProfessor'] ?? null;

    if (!is_int($idClassSection) || !is_int($department_id) || !is_int($usernameProfessor) || !is_string($justification) || empty($justification)) {
        echo json_encode([
            "status" => "error",
            "message" => "Parámetros inválidos. Asegúrese de enviar un ID de sección, un ID de departamento y una justificación válida."
        ]);
        exit;
    }

    try {
        // Llamar al método del DAO
        $result = $dao->deleteClassSectionsByDepartmentHead($idClassSection, $department_id, $justification, $usernameProfessor);
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Error en la ejecución de deleteClassSectionsByDepartmentHead(): " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Método no permitido. Use POST."
    ]);
}
?>
