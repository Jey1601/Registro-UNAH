<?php

$path = '../../../../';
include_once $path . "src/DAO/ClassSectionsProfessorDAO.php";
$dao = new ClassSectionsProfessorDAO();

/**
 * Endpoint que recibe una solicitud POST para obtener la URL del video de presentación de una clase.
 * 
 * Este endpoint valida que el método de la solicitud sea POST y extrae los parámetros 
 * necesarios del cuerpo de la solicitud JSON. Luego, llama al método `getUrlPresentationVideo`
 * para obtener la URL del video de la presentación de la clase.
 * 
 * En caso de éxito, devuelve la URL del video en formato JSON.
 * Si los parámetros no son válidos o el método de solicitud no es POST, devuelve un mensaje de error.
 *
 * @api {post} http://localhost:8080/api/post/academicPlanning/
 * @apiName PostUrlPresentationVideo.php
 * @apiGroup Enrollment
 *
 * @apiParam {int} idClassSection Identificador de la sección de clase.
 * @apiParam {int} idProfessor Identificador del profesor.
 *
 * @apiSuccess {String} status Estado de la operación ("success" o "error").
 * @apiSuccess {String} urlVideo URL del video de la presentación.
 *
 * @apiError (Error 400) InvalidArguments Parámetros inválidos enviados en la solicitud.
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

    $idClassSection = $_POST['idClassSection'] ?? null;
    $idProfessor = $_POST['idProfessor'] ?? null;

    if (!is_int($idClassSection) || !is_int($idProfessor)) {
        echo json_encode([
            "status" => "error",
            "message" => "Parámetros inválidos. Asegúrese de enviar IDs válidos para la sección de clase y el profesor."
        ]);
        exit;
    }

    try {
        $result = $dao->getUrlPresentationVideo($idClassSection, $idProfessor);
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Error al obtener la URL del video de presentación: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Método no permitido. Use POST."
    ]);
}
?>
