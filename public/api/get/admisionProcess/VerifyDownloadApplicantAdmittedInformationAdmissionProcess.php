<?php
    /*
    * @author Alejandro Moya 20211020462
    * @created Noviembre de 2024
    */
header("Content-Type: application/json");

$path = '../../../../';
include_once $path."src/DAO/DownloadApplicantAdmittedInformationAdmissionProcessDAO.php";

$daoDownloadApplicantAdmittedInformationAdmissionProcess = new DownloadApplicantAdmittedInformationAdmissionProcessDAO();

try {
    $isDownloadApplicantAdmittedInformationAdmissionProcessOpen = $daoDownloadApplicantAdmittedInformationAdmissionProcess->getVerifyDownloadApplicantAdmittedInformationAdmissionProcess();

    if ($isDownloadApplicantAdmittedInformationAdmissionProcessOpen) {
        echo json_encode([
            "status" => "success",
            "message" => "El proceso de Descarga de aspirantes adminitos está abierto."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "El proceso de Descarga de aspirantes adminitos no está disponible en este momento."
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Ocurrió un error al verificar el proceso de Descarga de aspirantes adminitos: " . $e->getMessage()
    ]);
}

?>
