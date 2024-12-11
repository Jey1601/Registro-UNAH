<?php

const PATH = '../../../../src';
include_once PATH.'/DAO/StudentDAO.php';

header('Content-Type: application/json');

try {
    if (!isset($_POST['idStudent'], $_POST['reasons'], $_FILES['document'], $_FILES['evidence'], $_POST['idsClassSections'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Faltan datos requeridos en la solicitud.'
        ]);
        exit;
    }

    // Extraer datos del POST
    $idStudent = $_POST['idStudent'];
    $reasons = $_POST['reasons'];
    $document = file_get_contents($_FILES['document']['tmp_name']);
    $evidence = file_get_contents($_FILES['evidence']['tmp_name']);
    $idsClassSections = explode(',', $_POST['idsClassSections']); //Se espera un arreglo de IDs de secciones a cancelar
    
    if (!is_array($idsClassSections)) {
        echo json_encode([
            'success' => false,
            'message' => 'El parámetro idsClassSections debe ser un arreglo válido.'
        ]);
        exit;
    }

    $controller = new StudentDAO();
    $response = $controller->createRequestExcepcionalCancellation($idStudent, $reasons, $document, $evidence, $idsClassSections);

    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Ocurrió un error al procesar la solicitud.',
        'error' => $e->getMessage()
    ]);
}

?>