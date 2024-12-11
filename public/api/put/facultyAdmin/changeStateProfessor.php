<?php
/**
 * @author @AngelNolasco
 * @created 08/12/2024
 */
const PATH = '../../../../src';
include_once PATH.'/DAO/FacultyAdminDAO.php';

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

    // Obtener el cuerpo de la solicitud
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['idProfessor'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Numero de empleado de docente no definido o nulo.'
        ]);
        exit;
    }

    $idProfessor = intval($input['idProfessor']); //Se espera el ID del docente cuyo estado se quiere cambiar
    $controlador = new FacultyAdminDAO();
    $response = $controlador->changeStatusProfessor($idProfessor);

    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Ocurrió un error al procesar la solicitud.',
        'error' => $e->getMessage()
    ]);
}

?>