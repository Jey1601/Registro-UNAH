<?php

header("Content-Type: application/json"); 

$path = '../../../../';
include_once $path."src/DAO/AdmissionProccessDAO.php";


$dao = new AdmissionProccessDAO();


echo json_encode($dao->getAdmissionProcess());



?>
