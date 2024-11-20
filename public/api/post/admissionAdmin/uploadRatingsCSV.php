<?php
include_once '../../../../src/DAO/AdmissionAdminDAO.php';

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