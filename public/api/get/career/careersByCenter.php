<?php

header("Content-Type: application/json"); 


include_once "../../../../../src/DAO/CareerDAO.php";


$dao = new CareerDAO();


// Leer el parámetro 'id_center' desde la URL
if (isset($_GET['id_center'])) {
    $id_center = $_GET['id_center'];  // Obtener el valor de 'id_center' desde la URL
} else {
    echo json_encode(["message" => "id_center parameter is missing"]);
    exit;  // Detener la ejecución si el parámetro no está presente
}

$careers = $dao->getCareersBy($id_center);

if (empty($careers)) {
   
    echo json_encode(["message" => "No regional centers found"]);
} else {
    
    echo json_encode($careers);
}

?>