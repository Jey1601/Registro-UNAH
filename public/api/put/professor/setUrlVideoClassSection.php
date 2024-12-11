<?php
/**
 * @author @AngelNolasco
 * @created 09/12/2024
 */
const PATH = '../../../../src';
include_once PATH.'/DAO/ProfessorDAO.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
        echo json_encode([
            'success' => false,
            'message' => 'Método HTTP no permitido.'
        ]);
        exit;
    }

    // Obtener el cuerpo de la solicitud
    $inputBody = json_decode(file_get_contents('php://input'), true);

    if (!(isset($inputBody['idProfessor']) && isset($inputBody['urlVideo']) && isset($inputBody['idClassSection']))) {
        echo json_encode([
            'status' => 'warning',
            'message' => 'Parametros no definidos o nulos.'
        ]);
        exit;
    }

    $idProfessor = intval($inputBody['idProfessor']);
    $urlVideo = $inputBody['urlVideo'];
    $idClassSection = intval($inputBody['idClassSection']);
    $controller = new ProfessorsDAO();
    $response = $controller->setUrlVideoClass($idProfessor, $urlVideo, $idClassSection);

    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Ocurrió un error al procesar la solicitud.',
        'error' => $e->getMessage()
    ]);
}

?>