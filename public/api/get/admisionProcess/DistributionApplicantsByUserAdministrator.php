<?php

header("Content-Type: application/json");
$path = '../../../../';
include_once $path."src/DAO/AdmissionAdminDAO.php";

$daoAdmissionAdmin = new AdmissionAdminDAO(); 
$result = $daoAdmissionAdmin->DistributionApplicantsByUserAdministrator();

echo json_encode($result);

?>

<?php




