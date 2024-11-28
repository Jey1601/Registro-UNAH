<?php

header("Content-Type: application/json"); 


include_once "../../../../src/DAO/ApplicantDAO.php";


if (isset($_GET['user'])) {
    $user = $_GET['user'];  
    
   
    $daoApplicant = new ApplicantDAO();
    $dataApplicants = $daoApplicant->getPendingCheckData($user);
  
} 



?>
