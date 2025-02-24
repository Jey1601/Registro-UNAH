<?php
/**
 * Endpoint para la carga de un archivo CSV para la subida de notas de los exámenes de admisión.
 */
$path = '../../../../';
include_once $path.'src/DAO/AdmissionAdminDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvFile'])) {
    $fileTmpPath = $_FILES['csvFile']['tmp_name'];
    $csvController = new AdmissionAdminDAO();
    $response = $csvController->readCSVFile($_FILES['csvFile']);
} else {
    $response = [
        'success' => false,
        'message' => 'Metodo no permitido.'
    ];
    exit;
}

echo json_encode($response);

?>