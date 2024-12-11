<?php

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

class PHPMailerConfig {
    public static function getConfiguredMailer(): PHPMailer {
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

    public static function sendEmail(string $to, string $subject, string $body): void {
        $mail = self::getConfiguredMailer();
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        try {
            $mail->send();
        } catch (Exception $e) {
            throw new Exception("Error al enviar el correo: {$mail->ErrorInfo}");
        }
    }
}
