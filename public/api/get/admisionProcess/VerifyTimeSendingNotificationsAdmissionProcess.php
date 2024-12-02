<?php

header("Content-Type: application/json");

$path = '../../../../';
include_once $path."src/DAO/SendingNotificationsAdmissionProcessDAO.php";

$daoSendingNotificationsAdmissionProcess = new SendingNotificationsAdmissionProcessDAO();

try {
    $isSendingNotificationsAdmissionProcessOpen = $daoSendingNotificationsAdmissionProcess->getVerifyTimeSendingNotificationsAdmissionProcess();

    if ($isSendingNotificationsAdmissionProcessOpen) {
        echo json_encode([
            "status" => "success",
            "message" => "La hora del proceso  de envio de notificaciones sobre el resultado de examenes de admision está abierto."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "La hora del proceso  de envio de notificaciones sobre el resultado de examenes de admision no está disponible en este momento."
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Ocurrió un error al verificar La hora del proceso  de envio de notificaciones sobre el resultado de examenes de admision: " . $e->getMessage()
    ]);
}

?>
