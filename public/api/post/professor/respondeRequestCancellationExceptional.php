<?php
/**
 * @author @AngelNolasco
 * @created 10/12/2024
 */

const PATH = '../../../../src';
include_once PATH.'/DAO/ProfessorDAO.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Metodo no permitido.'
    ]);
    exit;
}
if (!isset($_POST['idProfessor'], $_POST['idRequest'], $_POST['idStudent'], $_POST['arrayClassSectionIdResolution'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Parametros no definidos o nulos.'
    ]);
    exit;
}

/**
 * EJEMPLO DE ESTRUCTURA QUE DEBE TENER EL ARRAY
 * $arrayClassSectionIdResolution = [
 *   [
 *       'idSection' => 1,
 *       'resolution' => 1,
 *   ],
 *   [
 *       'idSection' => 2,
 *       'resolution' => 1
 *   ],
 *   [
 *       'idSection' => 7,
 *       'resolution' => 0
 *   ]
 *   ];
 */

$idProfessor = $_POST['idProfessor'];
$idRequest = $_POST['idRequest'];
$idStudent = $_POST['idStudent'];
$arrayClassSectionIdResolution = $_POST['arrayClassSectionIdResolution'];
$controller = new ProfessorsDAO();
$response1 = $controller->respondRequestCancellationExceptional($idProfessor, $idRequest);
$response2 = $controller->respondeRequestListSectionClass($idProfessor, $idRequest, $idStudent, $arrayClassSectionIdResolution);
$finalResponse [] = $response1;
$finalResponse [] = $response2;

echo json_encode($finalResponse);

?>