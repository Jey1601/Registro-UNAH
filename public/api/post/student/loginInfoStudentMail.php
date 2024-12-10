<?php

$path = '../../../../';

include_once $path."src/DAO/util/mail.php";

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $type = $_POST['type'] ?? 'users_login'; 

    try {
        $mailer = new mail();

        $mailer->sendStudentsLogin($mailer->connection, $type, $mailer->maxEmailsPerDay);

        $response['success'] = true;
        $response['message'] = "Correos enviados correctamente.";
    } catch (Exception $e) {
        $response['message'] = "Error al enviar los correos: " . $e->getMessage();
    }
} 
else 
{
    $response['message'] = "Método no permitido.";
}

echo json_encode($response);
?>

