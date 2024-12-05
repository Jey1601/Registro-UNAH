<?php

header("Content-Type: application/json"); 

$path ='../../../../';
include_once $path."src/DAO/DepartmentDAO.php";


$dao = new DepartmentDAO();


// Leer el parámetro 'id_Faculty' desde la URL
if (isset($_GET['id_Faculty'])) {
    $id_Faculty = $_GET['id_Faculty'];  // Obtener el valor de 'id_Faculty' desde la URL
} else {
    echo json_encode(["message" => "id_Faculty parameter is missing"]);
    exit;  // Detener la ejecución si el parámetro no está presente
}

$deparments = $dao->getDepartmentsByFaculty($id_Faculty);

if (empty($deparments)) {
   
    echo json_encode(["message" => "No se encontraron departamentos"]);
} else {
    
    echo json_encode($deparments);
}

?>