<?php
    /*
    * @author Alejandro Moya 20211020462
    * @created Noviembre de 2024
    */
header("Content-Type: application/json");
$path = '../../../../';
include_once $path."src/DAO/AdmissionAdminDAO.php";

$daoAdmissionAdmin = new AdmissionAdminDAO(); 
$result = $daoAdmissionAdmin->DistributionApplicantsByUserAdministrator();

echo json_encode($result);

?>

<?php




