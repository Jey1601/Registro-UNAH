<?php

header("Content-Type: application/json"); 


include_once "../../../../src/DAO/ApplicantDAO.php";




$daoApplicant = new ApplicantDAO();


$dataApplicants = $daoApplicant->getPendingCheckData('admin08011990021');


?>
