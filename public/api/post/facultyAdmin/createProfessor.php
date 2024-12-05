<?php
    /** 
     * Endpoint para la creacion de docente
    */
    const PATH = '../../../../src';
    include_once PATH.'/DAO/FacultyAdminDAO.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode([
            'success' => false,
            'message' => 'Metodo no permitido.'
        ]);
        exit;
    }

    $inputBody = json_decode(file_get_contents('php://input'), true);
    $imageProfessor = $_FILES['applicantIdDocument'];

    $firstName = trim($inputBody['firstName']);
    $secondName = trim($inputBody['secondName']);
    $thirdName = trim($inputBody['thirdName']);
    $firstLastname = trim($inputBody['firstLastname']);
    $secondLastname = trim($inputBody['secondLastname']);
    $email = trim($inputBody['email']);
    $image = file_get_contents($imageProfessor['tmp_name']);
    $idObligation = intval(trim($inputBody['idObligation']));
    $idRegionalCenter = intval(trim($inputBody['idRegionalCenter']));
    $idDepartment = intval(trim($inputBody['idDepartment']));

    $controller = new FacultyAdminDAO();
    $response = $controller->createProfessor($firstName, $secondName, $thirdName, $firstLastname, $secondLastname, $email, $image, $idObligation, $idRegionalCenter, $idDepartment);

    echo json_encode($response);

?>