<?php
//require '../DAO/util/mail.php';
require __DIR__.'/../DAO/util/getApplicantsEmail.php';
require __DIR__.'/../DAO/util/email_templates.php';
require __DIR__.'/../DAO/util/PHPMailer/PHPMailer.php';
require __DIR__.'/../DAO/util/PHPMailer/Exception.php';
require __DIR__.'/../DAO/util/PHPMailer/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$host = 'localhost';
$user = 'root';
$password = '12345';
$database = 'unah_registration';

$connection = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($connection->connect_error) {
    die("Error de conexión: " . $connection->connect_error);
}

echo "Conexión exitosa.\n";

function PHPMailerConfig() {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'admisiones.unah.is802@gmail.com';
    $mail->Password = 'wwdb wedd fcur guyy';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->setFrom('admisiones.unah.is802@gmail.com', 'Equipo de Admisiones');
    return $mail;
}

$mail = PHPMailerConfig();
//$mailObject = new mail();
$resultQuery = getApplicantsWithResults($connection);

if (!$resultQuery || $resultQuery->num_rows === 0) {
    echo "No se encontraron usuarios para enviar correos.<br>";
    return;
} else {
    $emailCount = 0;
    while ($row = $resultQuery->fetch_assoc()) {
        //if ($emailCount >= $maxEmailsPerDay) break;
    
        $placeholders = [
            'full_name' => $row['full_name'] ?? '',
            'password_user_applicant' => $row['password_user_applicant'] ?? ''
        ];
    
        $message = getTemplate('exam_results', $placeholders);
    
        try {
            $mail->addAddress($row['email_applicant']);
            $mail->isHTML(true);
            $mail->Subject = 'Admisiones UNAH';
            $mail->Body = $message;
            $mail->send();
            echo "Correo enviado a {$row['full_name']}<br>";
            $emailCount++;
        } catch (Exception $e) {
            echo "Error al enviar correo a {$row['email_applicant']}: {$mail->ErrorInfo}<br>";
        }
    
        $mail->clearAddresses();
    }
    
    echo "Se enviaron $emailCount correos.<br>";

}


?>