<?php
const PATH = '../../../../src';
include_once PATH.'/DAO/ProfessorDAO.php';

header('Content-Type: application/json');

try {
    // Verificar que la solicitud sea PUT
    if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
        echo json_encode([
            'success' => false,
            'message' => 'Método HTTP no permitido.'
        ]);
        exit;
    }

    $inputBody = json_decode(file_get_contents('php://input'), true);

    if (!isset($inputBody['idProfessor'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Numero de empleado de docente no definido o nulo.'
        ]);
        exit;
    }

    $idProfessor = intval($inputBody['idProfessor']); //Se espera el ID del docente cuyo estado se quiere cambiar
    $controller = new ProfessorsDAO();
    $response = $controller->getRequests($idProfessor);

    echo json_encode($response);
} catch (Exception $e) {
    // Manejar errores inesperados
    echo json_encode([
        'success' => false,
        'message' => 'Ocurrió un error al procesar la solicitud.',
        'error' => $e->getMessage()
    ]);
}

?>