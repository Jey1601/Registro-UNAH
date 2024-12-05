<?php
/**
 * Endpoint para la autenticacion de docentes.
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

$inputBody = json_decode(file_get_contents('php://input'), true);

$userProfessor = (int)$inputBody['userProfessor'];
$passwordProfessor = trim($inputBody['passwordProfessor']);

$regexPasswordValidation = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[+*\-_])[A-Za-z\d+*\-_]{8,}$/';
$validation = preg_match($regexPasswordValidation, $passwordProfessor);
if(!$validation){
    echo json_encode([
        'success' => false,
        'message' => 'Credenciales invalidas.'
    ]);
    exit;
}

$auth = new ProfessorsDAO();
$response = $auth->authProfessor($userProfessor, $passwordProfessor);

echo json_encode($response);
?>