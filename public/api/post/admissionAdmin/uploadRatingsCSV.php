<?php
include_once '../Registro-UNAH-ladingpage/src/admissionAdmin/admissionAdmin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Obtener el body de la consulta
    $bodyData = json_decode(file_get_contents('php://input'), true);

    if (isset($bodyData['csvData'])) {
        $csvController = new AdmissionAdminDAO();
        $response = $csvController->readCSVFile($bodyData['csvData']);
    } else {
        $response = [
            'httpCode' => http_response_code(400),
            'message' => 'No CSV data received'
        ];
    }


} else {
    $response = [
        'httpCode' => http_response_code(405),
        'message' => 'Method not allowed.'
    ];
    exit;
}

echo json_encode($response);

?>