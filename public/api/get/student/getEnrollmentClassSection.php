<?php
const PATH = '../../../../src';
include_once PATH.'/DAO/StudentDAO.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        echo json_encode([
            'status' => 'error',
            'message' => 'Método no valido.'
        ]);
        exit;
    }
    
    $idStudent = intval($_GET['idStudent']); 
    
    if (!isset($idStudent)) {
        echo json_encode([
            'status' => 'warning',
            'message' => 'Numero de cuenta de estudiante no definido o nulo.'
        ]);
        exit;
    }
    
    $controller = new StudentDAO();
    $response = $controller->getEnrollmentClassSection($idStudent);
    
    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Ocurrió un error al procesar la solicitud.',
        'error' => $e->getMessage()
    ]);
}

?>