<?php

$path = '../../../../';
include_once $path . "src/DAO/Enrollment/DatesEnrollmentProcessDAO.php"; 
$dao = new DatesEnrollmentProcessDAO(); 

/**
 * Endpoint que obtiene el calendario de fechas de matricula dado un proceso de matricula.
 * 
 * Este endpoint responde a una solicitud GET y llama al método 
 * `getEnrollmentProcessByDate` para obtener los procesos de inscripción por proceso de matricula.
 * 
 * En caso de éxito, devuelve los datos en formato JSON.
 *
 * @api {get} http://localhost:8080/api/get/enrollment/
 * @apiName VerifyDatesEnrollmentProcess
 * @apiGroup Enrollment
 *
 * @apiSuccess {String} status Estado de la operación ("success" o "error").
 * @apiSuccess {Array} data Los datos de los procesos de inscripción agrupados por fecha y tiempo.
 * @apiError (Error 500) InternalServerError Si ocurre un error en la ejecución del proceso.
 *
 * @throws Exception Si ocurre un error en la ejecución del proceso.
 * 
 * @author Alejandro Moya 20211020462
 * @created 09/12/2024
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $result = $dao->getEnrollmentProcessByEnrollmentProcess();
        if($result['status']=='success'){
            echo json_encode($result);
        }else{
            
        }
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Error al obtener El calendario de Matricula: " . $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Método no permitido. Use GET."
    ]);
}
?>
