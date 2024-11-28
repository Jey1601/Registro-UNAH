<?php

header("Content-Type: application/json");

include_once "../../../../src/DAO/AdmissionAdminDAO.php";

$daoAdmissionAdmin = new AdmissionAdminDAO(); 
$result = $daoAdmissionAdmin->DistributionApplicantsByUserAdministrator();

echo json_encode($result);

?>

<?php




