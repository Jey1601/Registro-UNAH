<?php
/**
 * ResetProfessors
 *
 * Gestiona el restablecimiento de contraseñas de docentes.
 *
 * @author Kenia Romero
 * @created 09/12/2024
 */

//Cargar dependencias
require 'PHPMailerConfig.php';
require 'jwt.php';
require 'email_templates.php';

use PHPMailer\PHPMailer\PHPMailer;

//Se estable una zona horario para que coincida con las fechas y horas que se almacenan en la base de datos.
date_default_timezone_set('America/Tegucigalpa');

class ResetProfessor{
/**
* Configuración de conexión a la base de datos.
* @param int $maxEmailsPerDay limita la cantidad de correos que se pueden enviar por día. Considerando que actualmente las 
* pruebas se realizan con un correo personal se tomaron en cuenta las limitantes que el mismo tiene; para un ámbito laboral 
* lo ideal será utilizar un correo corporativo que permite enviar más correos por día.
*/
    private $connection;
    private $mail;
    private $maxEmailsPerDay = 500;

    private $host = 'localhost';
    private $user = 'root';
    private $password = 'root';
    private $dbName = 'unah_registration';

//Constructor de la clase
//Establece la conexión a la base de datos
public function __construct()
{
    $this->connection = null;
    try {
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->dbName);
    } catch (Exception $error) {
        printf("Failed connection: %s\n", $error->getMessage());
    }

    return $this->connection;
}

public function getConnection() {
    return $this->connection;
}

/**
* Genera un token JWT para el restablecimiento de contraseña y lo almacena en la base de datos.
*
* @param mysqli $connection Conexión activa a la base de datos.
* @param int $userId ID del usuario para el que se solicita el restablecimiento.
*
* @return string El token generado.
*
* @author Kenia Romero
* @created 09/12/2024
*/
public function createPasswordResetRequest(mysqli $connection, int $userId): string {
    //Generar el token JWT
    $expiryTime = time() + 3600; // Tiempo actual + 1 hora
    $payload = ['userId' => $userId, 'exp' => $expiryTime];
    $token = JWT::generateToken($payload);
    $token_hash = hash("sha256",$token);

    //Convertir el tiempo de expiración a formato SQL
    $expiryDateTime = date("Y-m-d H:i:s", $expiryTime);

    //Almacenar el token en la tabla TokenUserProffesor
    $query = "INSERT INTO tokenuserprofessor (id_user_professor, token_professor) VALUES (?, ?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('is', $userId, $token_hash);
    $stmt->execute();
    $tokenId = $stmt->insert_id;
    $stmt->close();

    //Almacenar la relación en PasswordResetRequestsProfessors con tiempo de expiración
    $query = "INSERT INTO PasswordResetRequestsProfessors (id_user_professor, token_id_professor, token_expiry_professor) VALUES (?, ?, ?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('iis', $userId, $tokenId, $expiryDateTime);
    $stmt->execute();
    $stmt->close();

    return $token;
}

/**
* Envía un correo electrónico al usuario con el token de restablecimiento de contraseña.
*
* @param string $email Correo electrónico del usuario.
* @param string $token Token de restablecimiento.
*
* @throws Exception Si el correo no es válido o hay un error al enviar el correo.
*
* @author Kenia Romero
* @created 09/12/2024
*/
public function sendPasswordResetEmail(string $email, string $token) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("El correo proporcionado no es válido.");
    }

    $placeholders = ['full_name' => 'Docente', 'token' => $token];
    $message = getTemplate('reset_request', $placeholders);

    try {
        PHPMailerConfig::sendEmail($email, 'Restablecimiento de Contraseña', $message);
    } catch (Exception $e) {
        throw new Exception("Error al enviar el correo: {$e->getMessage()}");
    }
}

/**
* Actualiza la contraseña del usuario en la base de datos.
*
* @param mysqli $connection Conexión activa a la base de datos.
* @param int $userId ID del usuario.
* @param string $newPassword Nueva contraseña.
*
* @return bool Indica si la actualización fue exitosa.
*
* @author Kenia Romero
* @created 09/12/2024
*/
function updatePassword(mysqli $connection, int $userId, string $newPassword): bool {
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $query = "UPDATE UsersProfessors SET password_user_professor = :password WHERE id_user_professor = :userId";
    $stmt = $connection->prepare($query);

    return $stmt->execute([':password' => $hashedPassword, ':userId' => $userId]);
    
}

/**
* Marca un token como utilizado en la base de datos.
*
* @param mysqli $connection Conexión activa a la base de datos.
* @param string $token Token a marcar como utilizado.
*
* @return bool Indica si la operación fue exitosa.
*
* @author Kenia Romero
* @created 09/12/2024
*/ 
public function markTokenAsUsed(mysqli $connection, string $token): bool {
    $query = "UPDATE PasswordResetRequestsProfessors 
              SET used_token_professor = TRUE 
              WHERE token_id_professor = (SELECT id_token_user_professor 
                                 FROM TokenUserProfessor 
                                 WHERE token_professor = ?)";
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

/**
* Solicita el restablecimiento de contraseña para un usuario dado su correo electrónico.
*
* @param mysqli $connection Conexión activa a la base de datos.
* @param string $email Correo electrónico del usuario.
*
* @return bool Indica si la solicitud fue exitosa.
*
* @author Kenia Romero
* @created 09/12/2024
*/
public function requestPasswordReset(mysqli $connection, string $email): bool {

    $query = "SELECT id_professor FROM Professor WHERE email_professor = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($idProfessor);
        $stmt->fetch();
        $stmt->close();

        $query = "SELECT id_user_professor FROM UsersProfessors WHERE username_user_professor = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param('s', $idProfessor);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($idUserProfessor);
            $stmt->fetch();
            $stmt->close();

            $token = $this->createPasswordResetRequest($connection, $idUserProfessor);

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

/**
* Valida un token y verifica su expiración.
*
* @param string $token Token a validar.
* @param string $typeUser Tipo de usuario (en este caso, 'student').
* @param mysqli $connection Conexión activa a la base de datos.
*
* @throws Exception Si el token es inválido o ha expirado.
* 
* @return bool Indica si el token es válido.
*
* @author Kenia Romero
* @created 09/12/2024
*/
public function validateTokenAndProceed(string $token, string $typeUser, mysqli $connection) {
    $token_hash = hash("sha256", $token);

    $query = "SELECT PasswordResetRequestsProfessors.token_expiry_professor  
              FROM PasswordResetRequestsProfessors 
              JOIN TokenUserProfessor 
              ON PasswordResetRequestsProfessors.token_id_professor = TokenUserProfessor.id_token_user_student
              WHERE TokenUserProfessor.id_token_user_professor = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('s', $token_hash);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $currentTime = new DateTime();
        $tokenExpiry = new DateTime($row['token_expiry_professor']);

        if ($currentTime > $tokenExpiry) {
            throw new Exception("El token ha expirado.");
        }

        return true; 
    } else {
        throw new Exception("Token inválido.");
    }
}

/**
* Resetea la contraseña del usuario después de validar el token.
*
* @param string $token Token de restablecimiento.
* @param string $newPassword Nueva contraseña.
* @param mysqli $connection Conexión activa a la base de datos.
*
* @return bool Indica si el restablecimiento fue exitoso.
*
* @throws Exception Si no se pudo actualizar la contraseña.
*
* @author Kenia Romero
* @created 09/12/2024
*/
public function resetPasswordWithTokenValidation(string $token, string $newPassword, mysqli $connection) {

    $this->validateTokenAndProceed($token, 'professor', $connection);

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $token_hash = hash("sha256", $token);
    $query = "UPDATE UsersProfessors 
              SET password_user_professor = ? 
              WHERE id_user_professor = (SELECT id_user_professor 
                                       FROM TokenUserProfessor WHERE token_professor = ?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('ss', $hashedPassword, $token_hash);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception("No se pudo actualizar la contraseña.");
    }

    $this->markTokenAsUsed($connection, $token);

    return true;
}


}

