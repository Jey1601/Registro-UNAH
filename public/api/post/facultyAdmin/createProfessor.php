<?php
    /** 
     * Endpoint para la creacion de docente
     * @author @AngelNolasco
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
    $imageProfessor = $_FILES['professorPicture'];

    $firstName = trim($_POST['professorFirstName']);
    $secondName = trim($_POST['professorSecondName']);
    $thirdName = trim($_POST['professorThirdName']);
    $firstLastname = trim($_POST['professorFirstLastName']);
    $secondLastname = trim($_POST['professorSecondLastName']);
    $email = trim($_POST['professorEmail']);
    $image = file_get_contents($imageProfessor['tmp_name']);
    $idObligation = 1; //intval(trim($$_POST['idObligation']));
    $idRegionalCenter = intval(trim($_POST['professorCenter']));
    $idDepartment = intval(trim($_POST['professorDepartment']));

    $controller = new FacultyAdminDAO();
    $response = $controller->createProfessor($firstName, $secondName, $thirdName, $firstLastname, $secondLastname, $email, $image, $idObligation, $idRegionalCenter, $idDepartment);

    echo json_encode($response);

?>