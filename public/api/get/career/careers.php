<?php

header("Content-Type: application/json"); 


include_once "../../../../src/DAO/CareerDAO.php";


$dao = new RegionalCenterDAO("localhost", "root", "your_password", "unah_registration");


$careers = $dao->getCareersBy();

if (empty($regionalCenters)) {
   
    echo json_encode(["message" => "No regional centers found"]);
} else {
    
    echo json_encode($regionalCenters);
}

?>
