<?php




include_once "../../../../src/util/AdmissionMail.php";


$dao = new AdmissionMailer();


$dao ->LimitedMailing();

?>