<?php

header("Content-Type: application/json"); 

$path ='../../../../';
include_once $path."src/DAO/RegionalCenterDAO.php";


$dao = new RegionalCenterDAO();


// Leer el parámetro 'id_department' desde la URL
if (isset($_GET['id_department'])) {
    $id_department = $_GET['id_department'];  // Obtener el valor de 'id_department' desde la URL
} else {
    echo json_encode(["message" => "id_department parameter is missing"]);
    exit;  // Detener la ejecución si el parámetro no está presente
}

$regionalCenters = $dao->getRegionalCentersByDepartment($id_department);

if (empty($regionalCenters)) {
   
    echo json_encode(["message" => "No se encontraron departamentos"]);
} else {
    
    echo json_encode($regionalCenters);
}

?>