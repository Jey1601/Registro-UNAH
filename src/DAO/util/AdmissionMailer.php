<?php
// Clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

class AdmissionMailer
{
    private $connection;
    private $mail;
    private $maxEmailsPerDay = 500;

    private $host = 'localhost';
    private $user = 'root';
    private $password = '12345';
    private $dbName = 'unah_registration';


    public function __construct()
    {
        $this->connection = $this->DBConection($this->host, $this->user, $this->password, $this->dbName);
   
       
    }

    // Conexión a la base de datos
    private function DBConection($host, $user, $password, $database)
    {
        $connection = new mysqli($host, $user, $password, $database);
        if ($connection->connect_error) {
            die("Conexión fallida: " . $connection->connect_error);
        }
        return $connection;
    }

    // Consulta para obtener usuarios con resultados
    private function getUsersWithResults()
    {
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
        return $this->connection->query($sql);
    }

    // Configuración de PHPMailer
    private function PHPMailerConfig()
    {
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

    // Construir el mensaje del correo
    private function Message($full_name, $exams, $password_user_applicant)
    {
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

    // Obtener la hora de envío desde la tabla AdmissionProcess
    private function getSendingTime()
    {
        $sql = "SELECT timeof_sending_notifications_admission_process FROM AdmissionProcess 
        WHERE current_status_admission_process = 1 LIMIT 1";
        $result = $this->connection->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return $row['timeof_sending_notifications_admission_process'];
        }
        return null;
    }

    // Almacenar los correos enviados
    private function saveNotification($id_resolution, $email_sent, $date_sent)
    {
        $sql = "
            INSERT INTO NotificationsApplicationsResolution 
            (id_resolution_intended_undergraduate_applicant, email_sent_application_resolution, date_email_sent_application_resolution)
            VALUES (?, ?, ?)
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("iis", $id_resolution, $email_sent, $date_sent);
        $stmt->execute();
        $stmt->close();
    }

    // Enviar correo
    private function sendMail($email, $subject, $body, $id_resolution)
    {
        try {
            $this->mail->addAddress($email);
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            $this->mail->send();

            // Guardar notificación en la base de datos
            $date_sent = date('Y-m-d');
            $this->saveNotification($id_resolution, true, $date_sent);

            echo "Correo enviado a $email y registrado en la base de datos.<br>";
        } catch (Exception $e) {
            echo "Error al enviar correo a $email: {$this->mail->ErrorInfo}<br>";
        }
        $this->mail->clearAddresses();
    }

    // Enviar correos limitados
    public function LimitedMailing()
    {
        // Verificar la hora de envío
        $sendingTime = $this->getSendingTime();
        if (!$sendingTime || date('H:i:s') < $sendingTime) {
            echo "No es el momento configurado para enviar correos.<br>";
            return;
        }

        $result = $this->getUsersWithResults();
        if (!$result || $result->num_rows === 0) {
            echo "No se encontraron usuarios para enviar correos.<br>";
            return;
        }

        $emailCount = 0;
        $applicants = [];

        while ($row = $result->fetch_assoc()) {
            if ($emailCount >= $this->maxEmailsPerDay) break;

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
            if ($emailCount >= $this->maxEmailsPerDay) break;

            $full_name = $applicant_data['full_name'];
            $email = $applicant_data['email'];
            $password_user_applicant = $applicant_data['password_user_applicant'];

            // Construir el mensaje del correo con los exámenes del aspirante
            $examsDetails = "";
            foreach ($applicant_data['exams'] as $exam) {
                $examsDetails .= "<p><strong>{$exam['name_type_admission_tests']}:</strong> {$exam['rating_applicant']}</p>";
            }

            $message = $this->Message($full_name, $examsDetails, $password_user_applicant);

            // Llamada para enviar el correo
            $this->sendMail(
                $email,
                'Resultado Examen de Admisión - Universidad Nacional Autónoma de Honduras',
                $message,
                $password_user_applicant
            );

            $emailCount++;
        }

        echo "Se enviaron $emailCount correos hoy.<br>";
        print_r("Se enviaron $emailCount correos hoy");
    }

    // Cerrar la conexión
    public function closeConnection()
    {
        $this->connection->close();
    }
}



?>