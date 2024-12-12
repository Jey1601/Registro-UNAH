<?php
const PATH = '../../../../src';
include_once PATH.'/DAO/FacultyAdminDAO.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        echo json_encode([
            'success' => false,
            'message' => 'Método no valido.'
        ]);
        exit;
    }

    if (!isset($_GET['idFaculty'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Codigo de facultad no definido o nulo.'
        ]);
        exit;
    }

    $idFaculty = intval($_GET['idFaculty']);
    $controller = new FacultyAdminDAO();
    $response = $controller->getProfessorsByFaculty($idFaculty);

    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Ocurrió un error al procesar la solicitud.',
        'error' => $e->getMessage()
    ]);
}

?>