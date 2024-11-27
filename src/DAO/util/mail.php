<?php
//Cargar dependencias
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'getApplicantsEmail.php';
require 'email_templates.php';
require 'Code.php';
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


public function setConfirmationEmailApplicants($name, $email, $applicant_id_email_confirmation) {
   
    
    $mail = $this->PHPMailerConfig();
    $confirmation_code_email_confirmation = Code::generateAlphanumericCode(5);

    date_default_timezone_set('America/Tegucigalpa');

    $currentDate = date("Y-m-d H:i:s");
    $experied_email_confirmation = date("Y-m-d H:i:s", strtotime('+1 hour', strtotime($currentDate))); 
    $email_sent_email_confirmation = 1;

    $sql = "
        INSERT INTO
            `ConfirmationEmailApplicants` (
                applicant_id_email_confirmation,
                email_sent_email_confirmation,
                confirmation_code_email_confirmation,
                experied_email_confirmation
            )
        VALUES (?, ?, ?, ?);
    ";

    $stmt = $this->connection->prepare($sql);
    $stmt->bind_param("siss", $applicant_id_email_confirmation, $email_sent_email_confirmation, $confirmation_code_email_confirmation, $experied_email_confirmation);
   
    if ($stmt->execute()) {
        $placeholders = [
            'full_name' => $name,
            'verification_code' => $confirmation_code_email_confirmation,
        ];

        $message = getTemplate('verification_email', $placeholders);

        try {
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Admisiones UNAH';
            $mail->Body = $message;

            if ($mail->send()) {
            
                echo json_encode([
                    "status" => "info",
                    "message" => "Se ha enviado un código de confirmación a su correo."
                ]);

             
                
            }
        } catch (Exception $e) {
            echo json_encode([
                "status" => "danger",
                "message" => "No se ha podido enviar el código de verificación. Intente más tarde."
            ]);
        } finally {
            $mail->smtpClose(); // Cerrar conexión SMTP
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "No se pudo registrar la confirmación en la base de datos."
        ]);
    }

    $stmt->close();
}


public function getConfirmationEmailApplicants($applicant_id_email_confirmation,$confirmation_code_email_confirmation) {
    $mail = $this->PHPMailerConfig();
 
    
    date_default_timezone_set('America/Tegucigalpa');
    $currentDate = date("Y-m-d H:i:s");
   



    $sql = "
        select
            experied_email_confirmation,
            attempts_email_confirmation
        from `ConfirmationEmailApplicants`
        WHERE
            applicant_id_email_confirmation = ?
            AND confirmation_code_email_confirmation = ?;
    ";

    $stmt = $this->connection->prepare($sql);
    $stmt->bind_param("ss", $applicant_id_email_confirmation , $confirmation_code_email_confirmation);
   
    if ($stmt->execute()) {
        

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

                
            $experied_email_confirmation = $row['experied_email_confirmation'];
            $attempts_email_confirmation = $row['attempts_email_confirmation'] +1;

            if($currentDate > $experied_email_confirmation){
                echo json_encode([
                    "status" => "danger",
                    "message" => "Código de verificación expirado"
                ]);
            }else{
                echo json_encode([
                    "status" => "success",
                    "message" => "Su correo ha sido verificado"
                ]);

                //Ejecutar consulta de actualización

                $sql="UPDATE `ConfirmationEmailApplicants`
                    SET
                        status_email_confirmation = ?,
                        attempts_email_confirmation = ?
                    WHERE applicant_id_email_confirmation = ?
                    AND confirmation_code_email_confirmation = ?;";

                $status_email_confirmation = 'used'; 
                $stmt = $this->connection->prepare($sql);
                $stmt->bind_param("siss",  $status_email_confirmation, $attempts_email_confirmation, $applicant_id_email_confirmation , $confirmation_code_email_confirmation);
           
                $stmt->execute();
            }
                
          
        }else{
            echo json_encode([
                "status" => "error",
                "message" => "Código de verificación incorrecto"
            ]);
        }

    
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Ha ocurrido un error en la validación"
        ]);
    }

    $stmt->close();
}

//Configuración


/*Ejecutar el sistema: prueba para enviar confirmación
$connection = DBConection($host, $user, $password, $database);
sendEmails($connection, 'confirmation', $maxEmailsPerDay);
$connection->close();*/

}
?>
