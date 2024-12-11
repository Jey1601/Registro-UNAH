<?php

require 'PHPMailerConfig.php';
require 'jwt.php';
require 'email_templates.php';

use PHPMailer\PHPMailer\PHPMailer;

date_default_timezone_set('America/Tegucigalpa');

class ResetStudents{
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

public function getConnection() {
    return $this->connection;
}

public function createPasswordResetRequest(mysqli $connection, int $userId): string {
    //Generar el token JWT
    $expiryTime = time() + 3600; // Tiempo actual + 1 hora
    $payload = ['userId' => $userId, 'exp' => $expiryTime];
    $token = JWT::generateToken($payload);
    $token_hash = hash("sha256",$token);

    //Convertir el tiempo de expiración a formato SQL
    $expiryDateTime = date("Y-m-d H:i:s", $expiryTime);

    //Almacenar el token en la tabla TokenUserStudent
   // Almacenar o actualizar el token en la tabla TokenUserStudent
    $query = "INSERT INTO TokenUserStudent (id_user_student, token_student) 
    VALUES (?, ?) 
    ON DUPLICATE KEY UPDATE token_student = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('iss', $userId, $token_hash, $token_hash);
    $stmt->execute();
    $tokenId = $stmt->insert_id;
    $stmt->close();

    //Almacenar la relación en PasswordResetRequestsStudents con tiempo de expiración
    $query = "INSERT INTO PasswordResetRequestsStudents (id_user_student, token_id_student, token_expiry_student) VALUES (?, ?, ?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('iis', $userId, $tokenId, $expiryDateTime);
    $stmt->execute();
    $stmt->close();

    return $token;
}

public function sendPasswordResetEmail(string $email, string $token) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("El correo proporcionado no es válido.");
    }

    $placeholders = ['full_name' => 'Estudiante', 'token' => $token];
    $message = getTemplate('reset_request', $placeholders);

    try {
        PHPMailerConfig::sendEmail($email, 'Restablecimiento de Contraseña', $message);
    } catch (Exception $e) {
        throw new Exception("Error al enviar el correo: {$e->getMessage()}");
    }
}

function updatePassword(mysqli $connection, int $userId, string $newPassword): bool {
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $query = "UPDATE UsersStudents SET password_user_student = :password WHERE id_user_student = :userId";
    $stmt = $connection->prepare($query);

    return $stmt->execute([':password' => $hashedPassword, ':userId' => $userId]);
    
}

public function markTokenAsUsed(mysqli $connection, string $token): bool {
    $query = "UPDATE PasswordResetRequestsStudents 
              SET used_token_student = TRUE 
              WHERE token_id_student = (SELECT id_token_user_student 
                                 FROM TokenUserStudent 
                                 WHERE token_student = ?)";
    $stmt = $connection->prepare($query);

    if ($stmt === false) {
        throw new Exception("Error en la preparación de la consulta: " . $connection->error);
    }

    // Vincula el parámetro
    $stmt->bind_param('s', $token);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}

public function requestPasswordReset(mysqli $connection, string $email): bool {
    //Buscar el id_student en la tabla Students
    $query = "SELECT id_student FROM Students WHERE email_student = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($idStudent);
        $stmt->fetch();
        $stmt->close();

        //Verificar si existe una relación en UsersStudents
        $query = "SELECT id_user_student FROM UsersStudents WHERE username_user_student = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param('s', $idStudent);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($idUserStudent);
            $stmt->fetch();
            $stmt->close();

            //Generar y almacenar el token
            $token = $this->createPasswordResetRequest($connection, $idUserStudent);

            //Enviar el correo electrónico
            $this->sendPasswordResetEmail($email, $token);

            return true;
        } else {
            echo "No se encontró un usuario asociado a este correo.";
            return false;
        }
    } else {
        echo "No se encontró ningún estudiante con ese correo electrónico.";
        return false;
    }
}

public function validateTokenAndProceed(string $token, string $typeUser, mysqli $connection) {
    $token_hash = hash("sha256", $token);

    $query = "SELECT PasswordResetRequestsStudents.token_expiry_student 
              FROM PasswordResetRequestsStudents 
              JOIN TokenUserStudent 
              ON PasswordResetRequestsStudents.token_id_student = TokenUserStudent.id_token_user_student
              WHERE TokenUserStudent.token_student = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('s', $token_hash);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $currentTime = new DateTime();
        $tokenExpiry = new DateTime($row['token_expiry_student']);

        if ($currentTime > $tokenExpiry) {
            throw new Exception("El token ha expirado.");
        }

        return true; 
    } else {
        throw new Exception("Token inválido.");
    }
}

public function resetPasswordWithTokenValidation(string $token, string $newPassword, mysqli $connection) {
    //Validar el token y verificar su expiración
    $this->validateTokenAndProceed($token, 'student', $connection);

    //Actualizar la contraseña
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $token_hash = hash("sha256", $token);
    $query = "UPDATE UsersStudents 
              SET password_user_student = ? 
              WHERE id_user_student = (SELECT id_user_student 
                                       FROM TokenUserStudent WHERE token_student = ?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('ss', $hashedPassword, $token_hash);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception("No se pudo actualizar la contraseña.");
    }

    //Marcar el token como usado
    $this->markTokenAsUsed($connection, $token);

    return true;
}


}

