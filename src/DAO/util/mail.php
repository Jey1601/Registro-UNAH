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
  También obtenemos el email de los aspirantes, su contraseña para acceder al sistema y elegir una carrera,
  la nota que obtuvieron en el examen de admisión y un ID de la resolución de su examen, de esta manera podrémos
  almacenar la información referente al envio de correo a cada aspiante más adelante.
*/
function getUsersWithResults($connection) {
    $sql = "
          SELECT 
            Applicants.id_applicant,
            CONCAT(
                Applicants.first_name_applicant, ' ',
                IFNULL(Applicants.second_name_applicant, ''), ' ',
                IFNULL(Applicants.third_name_applicant, ''), ' ',
                Applicants.first_lastname_applicant, ' ',
                IFNULL(Applicants.second_lastname_applicant, '')
            ) AS full_name,
            Applicants.email_applicant,
            UsersApplicants.password_user_applicant,
            `TypesAdmissionTests`.name_type_admission_tests,
            `RatingApplicantsTest`.rating_applicant
        FROM 
            Applicants
        LEFT JOIN 
            Applications ON Applicants.id_applicant = Applications.id_applicant
        LEFT JOIN 
            UsersApplicants ON Applications.id_admission_application_number = UsersApplicants.password_user_applicant
        LEFT JOIN
            RatingApplicantsTest ON Applications.id_admission_application_number = RatingApplicantsTest.id_admission_application_number
        LEFT JOIN `TypesAdmissionTests` ON `RatingApplicantsTest`.id_type_admission_tests = `TypesAdmissionTests`.id_type_admission_tests   
        WHERE 
            Applicants.status_applicant = 1 AND status_rating_applicant_test =1;";
    return $connection->query($sql);
}

//Configuración PHPMailer
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

//Correo con información esencial (nombre completo, calificación obtenida, contraseña y enlace)
function Message($full_name, $exams, $password_user_applicant) {
    // Construir la parte de los exámenes dinámicamente
    $exams_details = "";
    foreach ($exams as $exam) {
        $exams_details .= "<p><strong>Nombre del examen: </strong>{$exam['name_type_admission_tests']}</p>";
        $exams_details .= "<p><strong>Resultado: </strong>{$exam['rating_applicant']}</p>";
    }

    // Crear el mensaje HTML
    return "
        <html>
            <body>
                <h2>Hola, $full_name</h2>
                <p>Te informamos que obtuviste los siguientes resultados en tu examen de admisión para 
                la máxima casa de estudios:</p>
                $exams_details
                <p>Puedes acceder al enlace adjunto en este correo para seleccionar la carrera en la que
                te gustaría inscribirte. Usa tu número de identidad como usuario y la siguiente
                contraseña para ingresar al sitio.</p>
                <p><strong>Contraseña: </strong>$password_user_applicant</p>
                <p><a href='https://www.facebook.com' target='_blank'>Elige tu carrera aquí</a></p>
                <p>Saludos,<br>El equipo de Admisiones</p>
            </body>
        </html>
    ";
}
//Función para obtener la hora de envío desde la tabla AdmissionProcess verifica que el proceso de admisión este vigente
function getSendingTime($connection) {
    $sql = "SELECT timeof_sending_notifications_admission_process FROM AdmissionProcess 
    WHERE current_status_admission_process = 1 LIMIT 1";
    $result = $connection->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        return $row['timeof_sending_notifications_admission_process'];
    }
    return null;
}

//Almacena los correos que ya fueron enviados
function saveNotification($connection, $id_resolution, $email_sent, $date_sent) {
    //La cosulta inserta nuevo registro en la tabla utilizando valores preparados (?) para evitar inyecciones SQL
    $sql = "
        INSERT INTO NotificationsApplicationsResolution 
        (id_resolution_intended_undergraduate_applicant, email_sent_application_resolution, date_email_sent_application_resolution)
        VALUES (?, ?, ?)
    ";
    $stmt = $connection->prepare($sql);
    //"iis" especifica los tipos de los parámetros: i: Entero ($id_resolution y $email_sent). s: Cadena ($date_sent).
    $stmt->bind_param("iis", $id_resolution, $email_sent, $date_sent);
    $stmt->execute();
    $stmt->close();
}

//Enviar correo
function sendMail($connection, $mail, $email, $subject, $body, $id_resolution) {
    try {
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();

        //Guardar notificación en la base de datos
        $date_sent = date('Y-m-d');
        saveNotification($connection, $id_resolution, true, $date_sent);

        echo "Correo enviado a $email y registrado en la base de datos.<br>";
    } catch (Exception $e) {
        echo "Error al enviar correo a $email: {$mail->ErrorInfo}<br>";
    }
    $mail->clearAddresses();
}

//Enviar correos limitados
function LimitedMailing($connection, $maxEmailsPerDay) {
    //Verificar la hora de envío
    $sendingTime = getSendingTime($connection);
    if (!$sendingTime || date('H:i:s') < $sendingTime) {
        echo "No es el momento configurado para enviar correos.<br>";
        return;
    }

    $result = getUsersWithResults($connection);
    if (!$result || $result->num_rows === 0) {
        echo "No se encontraron usuarios para enviar correos.<br>";
        return;
    }

    $mail = PHPMailerConfig();
    $emailCount = 0;

    $applicants = [];
    
    while ($row = $result->fetch_assoc()) {
        if ($emailCount >= $maxEmailsPerDay) break;

        $id_applicant = $row['id_applicant'];
        $full_name = $row['full_name'];
        $email = $row['email_applicant'];
        $password_user_applicant = $row['password_user_applicant'];
        $name_type_admission_tests = $row['name_type_admission_tests'];
        $rating_applicant = $row['rating_applicant'];
    
        // Si el aspirante no existe en el arreglo, se inicializa
        if (!isset($applicants[$id_applicant])) {
            $applicants[$id_applicant] = [
                'full_name' => $full_name,
                'email' => $email,
                'password_user_applicant' => $password_user_applicant,
                'exams' => []
            ];
        }
        
         // Agregar el examen al arreglo del aspirante
            $applicants[$id_applicant]['exams'][] = [
                'name_type_admission_tests' => $name_type_admission_tests,
                'rating_applicant' => $rating_applicant
            ];

       
    }

    foreach ($applicants as $id_applicant => $applicant_data) {
        if ($emailCount >= $maxEmailsPerDay) break;
    
        $full_name = $applicant_data['full_name'];
        $email = $applicant_data['email'];
        $password_user_applicant = $applicant_data['password_user_applicant'];
    
        // Construir el mensaje del correo con los exámenes del aspirante
        $examsDetails = "";
        foreach ($applicant_data['exams'] as $exam) {
            $examsDetails .= "<p><strong>{$exam['name_type_admission_tests']}:</strong> {$exam['rating_applicant']}</p>";
        }
        


        $message = Message($full_name, $examsDetails, $password_user_applicant);
        
    
        // Llamada para enviar el correo
        sendMail(
            $connection,
            $mail,
            $email,
            'Resultado Examen de Admisión - Universidad Nacional Autónoma de Honduras',
            $message,
            $password_user_applicant
        );
    
        $emailCount++;


    }

    echo "Se enviaron $emailCount correos hoy.<br>";
}

//Configuración de conexión y envío
$host = 'localhost';
$user = 'root';
$password = '12345';
$database = 'unah_registration';
//Máximo de correos que se pueden enviar en un día
$maxEmailsPerDay = 500;

//Ejecutar envio
$connection = DBConection($host, $user, $password, $database);
LimitedMailing($connection, $maxEmailsPerDay);
$connection->close();
?>


