<?php
include_once '../../../../src/DAO/AdmissionAdminDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvFile'])) {
    $fileTmpPath = $_FILES['csvFile']['tmp_name'];
    $csvController = new AdmissionAdminDAO();
    $response = $csvController->readCSVFile($_FILES['csvFile']);
    
    // //Obtener el body de la consulta
    // $bodyData = json_decode(file_get_contents('php://input'), true);

    // //printf($bodyData['csvData']);
    // if (isset($bodyData)) {
    //     $csvController = new AdmissionAdminDAO();
    //     $response = $csvController->readCSVFile($bodyData);
    // } else {
    //     $response = [
    //         'success' => false,
    //         'message' => 'Datos no recibidos.'
    //     ];
    // }
} else {
    $response = [
        'success' => false,
        'message' => 'Metodo no permitido.'
    ];
    exit;
}

echo json_encode($response);

?>