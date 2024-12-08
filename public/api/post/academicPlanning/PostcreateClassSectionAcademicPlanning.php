<?php

$path = '../../../../';
include_once $path . "src/DAO/ClassSectionsDAO.php";
$dao = new ClassSectionsDAO();

/**
 * Endpoint que recibe una solicitud POST para crear una nueva sección de clase.
 * 
 * Este endpoint valida que el método de la solicitud sea POST y extrae los parámetros necesarios
 * del cuerpo de la solicitud JSON. Luego, llama al método `createClassSection` para intentar
 * crear una nueva sección de clase en la base de datos.
 * 
 * En caso de éxito, devuelve un mensaje en formato JSON con el resultado de la operación.
 * Si los parámetros no son válidos o el método de solicitud no es POST, devuelve un mensaje de error.
 *
 * @api {post} http://localhost:8080/api/post/academicPlanning/
 * @apiName PostcreateClassSectionAcademicPlanning
 * @apiGroup AcademicPlanning
 *
 * @apiParam {int} id_class Identificador único de la clase.
 * @apiParam {int} id_dates_academic_periodicity_year Identificador del periodo académico.
 * @apiParam {int} id_classroom_class_section Identificador del salón de clases y sección.
 * @apiParam {int} id_academic_schedules Identificador del horario académico.
 * @apiParam {int} id_professor_class_section Identificador del profesor asignado a la sección.
 * @apiParam {int} numberof_spots_available_class_section Número de espacios disponibles para la sección.
 * @apiParam {bool} status_class_section Estado de la sección de clase (activo o inactivo).
 *
 * @apiSuccess {String} status Estado de la operación ("success", "warning", "error").
 * @apiSuccess {String} message Mensaje detallado de la operación (éxito, advertencia o error).
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
    
    // Recoger los parámetros del cuerpo de la solicitud
    $id_class = $_POST['id_class'];
    $id_dates_academic_periodicity_year = $_POST['id_dates_academic_periodicity_year'];
    $id_classroom_class_section = $_POST['id_classroom_class_section'];
    $id_academic_schedules = $_POST['id_academic_schedules'];
    $id_professor_class_section = $_POST['id_professor_class_section'];
    $numberof_spots_available_class_section = $_POST['numberof_spots_available_class_section'];
    $status_class_section = $_POST['status_class_section']; // True or False

    // Llamar al método createClassSection
    $result = $dao->createClassSection(
        $id_class,
        $id_dates_academic_periodicity_year,
        $id_classroom_class_section,
        $id_academic_schedules,
        $id_professor_class_section,
        $numberof_spots_available_class_section,
        $status_class_section
    );
    
    // Enviar la respuesta en formato JSON
    echo json_encode($result);
} else {
    echo json_encode(["error" => "Método no permitido. Use POST."]);
}
?>
