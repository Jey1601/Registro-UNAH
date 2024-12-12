<?php
require 'PHPMailerConfig.php';
require 'getUsersInfoForMail.php';
require 'email_templates.php';

Class mail{
    private $connection;
    private $mail;
    private $maxEmailsPerDay = 500;

    private $host = 'localhost';
    private $user = 'root';
    private $password = '12345';
    private $dbName = 'unah_registration';

//Conexión BD
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

public function sendConfirmation($name,$id_application,$email,$password){
    $mail = new PHPMailerConfig();

        $placeholders = [
            'full_name' => $name ?? '',
            'id_application' =>$id_application ?? '',
            'password' => $password ?? ''
         ];

        $message = getTemplate('confirmation', $placeholders);

        try {
            PHPMailerConfig::sendEmail($email, 'Admisiones UNAH', $message);
        } catch (Exception $e) {
            throw new Exception("Error al enviar el correo: {$e->getMessage()}");
        }
}

public function sendCareerAcceptanceNotification($email, $name, $career) {
    $mail = new PHPMailerConfig();
 
     //Preparar los datos para la plantilla
     $placeholders = [
         'full_name' => $name,
         'career_name' => $career,
     ];
 
     //Obtener el mensaje de la plantilla
     $message = getTemplate('career_acceptance', $placeholders);
 
     //Enviar el correo
     try {
        PHPMailerConfig::sendEmail($email, 'Felicidades! Has sido aceptado en tu carrera - UNAH', $message);
    } catch (Exception $e) {
        throw new Exception("Error al enviar el correo: {$e->getMessage()}");
    }
}

public function sendUserProfessor($name,$username,$email,$password){
    $mail = new PHPMailerConfig();

        $placeholders = [
            'full_name' => $name ?? '',
            'username' =>$username ?? '',
            'password' => $password ?? ''
         ];

        $message = getTemplate('user_professor', $placeholders);

        try {
            PHPMailerConfig::sendEmail($email, 'Admisiones UNAH', $message);
        } catch (Exception $e) {
            throw new Exception("Error al enviar el correo: {$e->getMessage()}");
        }
 
}

public function sendVerificationConfirmation($name,$id_application,$email){
    $mail = new PHPMailerConfig();

        $placeholders = [
            'full_name' => $name ?? '',
            'id_application' =>$id_application ?? ''
        ];

        $message = getTemplate('confirmation', $placeholders);

        try {
            PHPMailerConfig::sendEmail($email, 'Admisiones UNAH', $message);
        } catch (Exception $e) {
            throw new Exception("Error al enviar el correo: {$e->getMessage()}");
        }
}

public function sendStatusApplicationCorrect($email, $name){
    $mail = new PHPMailerConfig();

    // Preparar los datos para la plantilla
    $placeholders = [
        'full_name' => $name ?? '', // Aquí deberías pasar el ID de la solicitud si está disponible
    ];

    // Obtener el mensaje de la plantilla
    $message = getTemplate('confirmation_correct', $placeholders);

    // Enviar el correo
    try {
        PHPMailerConfig::sendEmail($email, 'Confirmación de solicitud - Admisiones UNAH', $message);
    } catch (Exception $e) {
        throw new Exception("Error al enviar el correo: {$e->getMessage()}");
    }
}

public function formatIncorrectFields($incorrectFields) {
    $formatted = '';
    if (is_array($incorrectFields) && count($incorrectFields) > 0) {
        $formatted = '<ul>';
        foreach ($incorrectFields as $field) {
            $formatted .= '<li>' . htmlspecialchars($field) . '</li>';
        }
        $formatted .= '</ul>';
    }
    return $formatted;
}

public function sendStatusApplicationVerify($email, $name, $incorrectFields, $description){
    $mail = new PHPMailerConfig();

    // Preparar los datos para la plantilla
    $placeholders = [
        'full_name' => $name ?? '',
        'id_application' => '123456', // Aquí deberías pasar el ID de la solicitud si está disponible
        'campos_incorrectos' => $this->formatIncorrectFields($incorrectFields),
        'descripcion' => $description ?? ''
    ];

    // Obtener el mensaje de la plantilla
    $message = getTemplate('exam_results_warning', $placeholders);

    // Enviar el correo
    try {
        PHPMailerConfig::sendEmail($email, 'Advertencia sobre solicitud - Admisiones UNAH', $message);
    } catch (Exception $e) {
        throw new Exception("Error al enviar el correo: {$e->getMessage()}");
    }
}

public function setConfirmationEmailApplicants($name, $email, $applicant_id_email_confirmation) {
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
            PHPMailerConfig::sendEmail($email, 'Admisiones UNAH', $message);

            echo json_encode([
                "status" => "info",
                "message" => "Se ha enviado un código de confirmación a su correo."
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "danger",
                "message" => "No se ha podido enviar el código de verificación. Intente más tarde."
            ]);

            throw new Exception("Error al enviar el correo: {$e->getMessage()}");
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
    $mail = new PHPMailerConfig();
 
    
    date_default_timezone_set('America/Tegucigalpa');
    $currentDate = date("Y-m-d H:i:s");
   
    $sql = "select experied_email_confirmation,
                   attempts_email_confirmation
            from  
                  `ConfirmationEmailApplicants`
            WHERE
                   applicant_id_email_confirmation = ?
            AND 
                   confirmation_code_email_confirmation = ?;
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
                      WHERE   applicant_id_email_confirmation = ?
                      AND     confirmation_code_email_confirmation = ?;";

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

public function sendRatings(/*$connection, $type, $maxEmailsPerDay*/) {
    $mail = new PHPMailerConfig();
    $type = 'exam_results';
    $maxEmailsPerDay = 500;
    // Obtener los usuarios según el tipo de mensaje
    $result = match ($type) {
        'exam_results' => getGroupedResultsPerApplicants($this->connection),
        default => null
    };

    if (!$result || empty($result)) {
        echo "No se encontraron usuarios para enviar correos.<br>";
        return;
    }

    $emailCount = 0;

    foreach ($result as $applicant) {
        if ($emailCount >= $maxEmailsPerDay) break;

        //Configurar el cuerpo del mensaje dependiendo del tipo
        $placeholders = [
            'full_name' => $applicant['full_name'],
            'password_user_applicant' => $applicant['password']
        ];

        if ($type === 'exam_results') {
            $placeholders['exams_details'] = generateExamDetails($applicant['exams']);
        }

        $message = getTemplate($type, $placeholders);

        try {
            PHPMailerConfig::sendEmail($email, 'Admisiones UNAH', $message);
        } catch (Exception $e) {
            throw new Exception("Error al enviar el correo: {$e->getMessage()}");
        }
    }
    
    echo "Se enviaron $emailCount correos.<br>";
}

//Enviar el usuario y contraseña de los estudiantes
public function sendStudentsLogin($full_name, $username_user_student, $password, $email) {
    $mail = new PHPMailerConfig();

    // Crear los datos para la plantilla
    $placeholders = [
        'full_name' => $full_name,
        'username_user_student' => $username_user_student,
        'password_user_student' => $password
    ];

    // Obtener la plantilla del mensaje
    $message = getTemplate('users_login', $placeholders);

    if (!$message) {
        echo "Error: No se pudo generar el mensaje.<br>";
        return;
    }
    
    try {
        PHPMailerConfig::sendEmail($email, 'Admisiones UNAH', $message);
    } catch (Exception $e) {
        throw new Exception("Error al enviar el correo: {$e->getMessage()}");
    }
}

}


