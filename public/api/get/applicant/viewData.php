<?php


header("Content-Type: application/json");

include_once "../../../../src/DAO/ApplicantDAO.php";

$dao = new ApplicantDAO();

$dao->viewData();


?>