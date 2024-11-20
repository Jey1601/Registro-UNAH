<?php


header("Content-Type: application/json");

include_once "../../../../src/DAO/ApplicantDAO.php";

$dao = new ApplicantDAO('localhost', 'prueba', '123', 'unah_registration');

$applicationsData = $dao->viewData();


?>