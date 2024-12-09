<?php
const PATH = '../../../../src';
include_once PATH.'/DAO/ProfessorDAO.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        echo json_encode([
            'success' => false,
            'message' => 'Método no valido.'
        ]);
        exit;
    }

    $idProfessor = intval($_GET['idProfessor']); //Se espera el ID del docente coordinador

    if (!(isset($idProfessor))) {
        echo json_encode([
            'success' => false,
            'message' => 'Numero de empleado de docente no definido o nulo.'
        ]);
        exit;
    }
    
    $controller = new ProfessorsDAO();
    $response = $controller->getRequests($idProfessor);
    
    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Ocurrió un error al procesar la solicitud.',
        'error' => $e->getMessage()
    ]);
}


?>