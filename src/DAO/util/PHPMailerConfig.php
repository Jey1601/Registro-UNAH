<?php
/**
 * PHPMailerConfig
 *
 * Contiene las configuraciones necesarias para el correcto manejo y envío de correos electronicos que informan
 * sobre diversar situaciones en diferentes módulos del sistema.
 *
 * @author Kenia Romero
 * @created 06/12/2024
 */

//Cargar dependencias
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

class PHPMailerConfig {

/**
* Por seguridad utilizamos una contraseña de aplicación obtenida luego de configurar la verificación de dos pasos de 
* nuestra cuenta de correo.
* En el primer uso de práctica se uso directamente la contraseña del correo electronico y el mismo fue bloqueado debido a la 
* actividad sospechosa.
*
* Configura y devuelve una instancia de PHPMailer lista para enviar correos.
*
* Esta función inicializa un objeto PHPMailer con la configuración necesaria
* para conectarse al servidor SMTP de Gmail y enviar correos electrónicos.
*
* @return PHPMailer Una instancia configurada de PHPMailer.
*
* @throws Exception Si hay un problema al crear o configurar el objeto PHPMailer.
*/
    public static function getConfiguredMailer(): PHPMailer {
        //Crea una instancia de PHPMailer
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

/**
 * Envía un correo electrónico utilizando PHPMailer.
 *
 * Esta función configura un objeto PHPMailer y envía un correo electrónico a la dirección especificada. Permite enviar
 * correos en formato HTML, con asunto y cuerpo personalizados.
 *
 * @param string $to      Dirección de correo electrónico del destinatario.
 * @param string $subject Asunto del correo electrónico.
 * @param string $body    Contenido del cuerpo del correo electrónico en formato HTML.
 *
 * @throws Exception Notifica errores si se presentan durante el envio del correo.
 *
 * @return void
 */
    public static function sendEmail(string $to, string $subject, string $body): void {
        // Crear y configurar el objeto PHPMailer
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
