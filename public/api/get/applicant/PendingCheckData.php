<?php

header("Content-Type: application/json"); 

$path = '../../../../';

include_once $path."src/DAO/ApplicantDAO.php";


if (isset($_GET['user'])) {
    $user = $_GET['user'];  
    
   
    $daoApplicant = new ApplicantDAO();
    $dataApplicants = $daoApplicant->getPendingCheckData($user);
  
} 



?>
