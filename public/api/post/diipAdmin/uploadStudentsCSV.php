<?php
/**
 * Endpoint para la carga de un archivo CSV lpara a creacion de estudiantes.
 * @author @AngelNolasco
 */

$path = '../../../../';
include_once $path.'src/DAO/DIIPAdminDAO.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return [
        'success' => false,
        'message' => 'Metodo no permitido.'
    ];
    exit;
}

if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] === UPLOAD_ERR_OK) {
// Información del archivo
$csvFile = [
    'tmp_csv' => $_FILES['csvFile']['tmp_name'],
    'name' => $_FILES['csvFile']['name'],
    'type' => $_FILES['csvFile']['type'],
    'size' => $_FILES['csvFile']['size']
];

// Llamar al método para insertar estudiantes
$controller = new DIIPAdminDAO();
$response = $controller->insertStudentsByCSV($csvFile);

// Responder al frontend con los resultados
header('Content-Type: application/json');
echo json_encode($response);

} else {
// Manejar errores de carga
$errorMessage = "Error al cargar el archivo CSV.";
if (isset($_FILES['csvFile']['error'])) {
    $errorCode = $_FILES['csvFile']['error'];
    switch ($errorCode) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            $errorMessage = "El archivo excede el tamaño permitido.";
            break;
        case UPLOAD_ERR_PARTIAL:
            $errorMessage = "El archivo solo se cargó parcialmente.";
            break;
        case UPLOAD_ERR_NO_FILE:
            $errorMessage = "No se envió ningún archivo.";
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            $errorMessage = "Falta la carpeta temporal.";
            break;
        case UPLOAD_ERR_CANT_WRITE:
            $errorMessage = "No se pudo escribir el archivo en el disco.";
            break;
        case UPLOAD_ERR_EXTENSION:
            $errorMessage = "Una extensión detuvo la subida del archivo.";
            break;
        default:
            $errorMessage = "Error desconocido al subir el archivo.";
    }
}

header('Content-Type: application/json');
echo json_encode([
    'success' => false,
    'message' => $errorMessage
]);
}

?>