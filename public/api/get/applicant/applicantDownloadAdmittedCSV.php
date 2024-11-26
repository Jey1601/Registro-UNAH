<?php
include_once "../../../../src/DAO/ApplicantDAO.php";

//Establecer cabeceras para que el navegador entienda que es un archivo descargable
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="aspirantesAdmitidos.csv"');
header('Cache-Control: no-cache, no-store, must-revalidate');

//Crear objecto DAO
$applicantDAO = new ApplicantDAO();
$applicants = $applicantDAO->getApplicantsAdmittedCSV();

echo $applicants;
?>