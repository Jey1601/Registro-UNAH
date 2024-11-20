<?php
//Clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

//Conexión BD
function DBConection($host, $user, $password, $database) {
    $connection = new mysqli($host, $user, $password, $database);
    if ($connection->connect_error) {
        die("Conexión fallida: " . $connection->connect_error);
    }
    return $connection;
}

/*
  Consulta para obtener usuarios.
  Se concatenan los nombres del aplicante bajo el alias "full_name",
  posteriormente, "IFNULL" verifica si el campo es NULL y, en ese caso, 
  lo reemplaza con una cadena vacía (''). Esto evita que aparezca NULL en el resultado concatenado.
*/
function getUsers($connection) {
    $sql = "SELECT 
    Applicants.id_applicant,
    CONCAT(
        Applicants.first_name_applicant, ' ',
        IFNULL(Applicants.second_name_applicant, ''), ' ',
        IFNULL(Applicants.third_name_applicant, ''), ' ',
        Applicants.first_lastname_applicant, ' ',
        IFNULL(Applicants.second_lastname_applicant, '')
    ) AS full_name,
    Applications.id_admission_application_number
FROM 
    Applicants
JOIN 
    Applications ON Applicants.id_applicant = Applications.id_applicant;
";
    return $connection->query($sql);
}

//PHPMailer
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

//Correo
function Message($full_name, $application_number) {
    return "
        <html>
            <body>
                <h2>Hola, $full_name</h2>
                <p>Te informamos que puedes acceder al enlace adjunto en este correo para seleccionar la carrera en la que
                te gustaría inscribirte. Usa tu número de identidad como usuario y la siguiente
                contraseña para ingresar al sitio.</p>
                <p><strong>Contraseña: </strong>$application_number</p>
                <p><a href='https://www.facebook.com' target='_blank'>Elige tu carrera aquí</a></p>
                <p>Saludos,<br>El equipo de Admisiones</p>
            </body>
        </html>
    ";
}

//Función para enviar correo
function sendMail($mail, $email, $subject, $body) {
    try {
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
        echo "Correo enviado a $email<br>";
    } catch (Exception $e) {
        echo "Error al enviar correo a $email: {$mail->ErrorInfo}<br>";
    } finally {
        $mail->clearAddresses();
    }
}

//Función para enviar correos a todos los usuarios
function SendToUsers($connection) {
    $result = getUsers($connection);
    $mail = PHPMailerConfig();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $full_name = $row['full_name'];
            $email = $row['email_applicant'];
            $application_number = $row['id_admission_application_number'];
            $message = Message($full_name, $application_number);
            sendMail($mail, $email, 'Resultado Examen de Admisión - Universidad Nacional Autónoma de Honduras', $message);
        }
    } else {
        echo "No se encontraron usuarios en la base de datos.";
    }
}

//Conexión BD
$host = 'localhost';
$user = 'root';
$password = 'root';
$database = 'unah_registration';

//Enviar correo
$connection = DBConection($host, $user, $password, $database);
SendToUsers($connection);
$connection->close();
?>
