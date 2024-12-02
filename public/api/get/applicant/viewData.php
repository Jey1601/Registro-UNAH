<?php


header("Content-Type: application/json");

$path = '../../../../';

include_once $path."src/DAO/ApplicantDAO.php";

$dao = new ApplicantDAO();

$dao->viewData();


?>