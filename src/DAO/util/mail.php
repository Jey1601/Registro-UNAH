<?php
//Cargar dependencias
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'getApplicantsEmail.php';
require 'email_templates.php';

use PHPMailer\PHPMailer\PHPMailer;

class  mail{

    private $connection;
    private $mail;
    private $maxEmailsPerDay = 500;

    private $host = 'localhost';
    private $user = 'root';
    private $password = '12345';
    private $dbName = 'unah_registration';


//Configuración de la base de datos
public function __construct()
{
    $this->connection = null;
    try {
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->dbName);
    } catch (Exception $error) {
        printf("Failed connection: %s\n", $error->getMessage());
    }
}

//Configuración de PHPMailer
private function PHPMailerConfig() {
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

//Enviar correos electrónicos
public function sendEmails( $type, $maxEmailsPerDay) {
    $mail = $this->PHPMailerConfig();

    //Obtener los usuarios según el tipo de mensaje
    $result = match ($type) {
        'confirmation' => getApplicantsForConfirmation($this->connection),
        'exam_results' => getApplicantsWithResults($this->connection),
        'approved' => getApprovedApplicants($this->connection),
        default => null
    };

    if (!$result || $result->num_rows === 0) {
        echo "No se encontraron usuarios para enviar correos.<br>";
        return;
    }

    $emailCount = 0;

    while ($row = $result->fetch_assoc()) {
        if ($emailCount >= $maxEmailsPerDay) break;

        $placeholders = [
            'full_name' => $row['full_name'] ?? '',
            'password_user_applicant' => $row['password_user_applicant'] ?? ''
        ];

        $message = getTemplate($type, $placeholders);

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

public function sendConfirmation($name,$id_application,$email){
    $mail = $this->PHPMailerConfig();


        $placeholders = [
            'full_name' => $name ?? '',
            'id_application' =>$id_application ?? ''
        ];

        $message = getTemplate('confirmation', $placeholders);

        try {
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Admisiones UNAH';
            $mail->Body = $message;
            $mail->send();
        } catch (Exception $e) {
            echo "Error al enviar correo a {$email}: {$mail->ErrorInfo}<br>";
        }

        $mail->clearAddresses();
 
}

//Configuración


/*Ejecutar el sistema: prueba para enviar confirmación
$connection = DBConection($host, $user, $password, $database);
sendEmails($connection, 'confirmation', $maxEmailsPerDay);
$connection->close();*/

}
?>
