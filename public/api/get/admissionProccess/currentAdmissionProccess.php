<?php

header("Content-Type: application/json"); 


include_once "../../../../../src/DAO/AdmissionProccessDAO.php";


$dao = new AdmissionProccessDAO();


echo json_encode($dao->getAdmissionProcess());



?>
