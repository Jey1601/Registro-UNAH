<?php

header("Content-Type: application/json"); 

$path ='../../../../';
include_once  $path."src/DAO/RegionalCenterDAO.php";


$dao = new RegionalCenterDAO();


$regionalCenters = $dao->getRegionalCenters();

if (empty($regionalCenters)) {
   
    echo json_encode(["message" => "No regional centers found"]);
} else {
    
    echo json_encode($regionalCenters);
}

?>

