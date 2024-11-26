<?php

header("Content-Type: application/json"); 


include_once "../../../../src/DAO/ApplicantDAO.php";




$daoApplicant = new ApplicantDAO();


$dataApplicants = $daoApplicant->getPendingCheckData('admin08011990021');
echo json_encode($dataApplicants);
if (empty($dataApplicants)) {
   
    echo json_encode(["message" => "No informacion de aspirantes encontrada"]);
} else {
    
    echo json_encode($dataApplicants);
}

?>