<?php

header("Content-Type: application/json");

include_once "../../../../src/DAO/SendingNotificationsAdmissionProcessDAO.php";

$daoSendingNotificationsAdmissionProcess = new SendingNotificationsAdmissionProcessDAO();

try {
    $isSendingNotificationsAdmissionProcessOpen = $daoSendingNotificationsAdmissionProcess->getVerifySendingNotificationsAdmissionProcess();

    if ($isSendingNotificationsAdmissionProcessOpen) {
        echo json_encode([
            "status" => "success",
            "message" => "El proceso de envio de notificaciones sobre el resultado de examenes de admision está abierto."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "El proceso de envio de notificaciones sobre el resultado de examenes de admision no está disponible en este momento."
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Ocurrió un error al verificar el proceso de envio de notificaciones sobre el resultado de examenes de admision: " . $e->getMessage()
    ]);
}

?>
