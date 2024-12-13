<?php
/**
 * Endpoint para la descarga de un archivo CSV con información de interés de los aspirantes. Información antes de la evaluación de los exámenes.
 * @author @AngelNolasco
 */

 $path = '../../../../';

 include_once $path."src/DAO/ApplicantDAO.php";

//Establecer cabeceras para que el navegador entienda que es un archivo descargable
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="aspirantes.csv"');
header('Cache-Control: no-cache, no-store, must-revalidate');

//Crear objecto DAO
$applicantDAO = new ApplicantDAO();
$applicants = $applicantDAO->getApplicantsInfoCSV();

echo $applicants;
?>