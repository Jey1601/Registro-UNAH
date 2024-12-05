<?php
/**
 * Endpoint para la carga de un archivo CSV lpara a creacion de estudiantes.
 */
$path = '../../../../';
include_once $path.'src/DAO/DIIPAdminDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvFile'])) {
    $fileTmpPath = $_FILES['csvFile']['tmp_name'];
    $csvController = new DIIPAdminDAO();
    $response = $csvController->insertStudentsByCSV($_FILES['csvFile']);
} else {
    $response = [
        'success' => false,
        'message' => 'Metodo no permitido.'
    ];
    exit;
}

echo json_encode($response);
?>