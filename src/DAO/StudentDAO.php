<?php
/**
 * Objeto de acceso a datos y controlador de estudiantes
 * 
 * @property string $host Direccion de host de base de datos
 * @property string $user Usuario de acceso a la base de datos
 * @property string $password Clave de acceso del respectivo usuario
 * @property string $dbName Nombre de la base de datos
 * @property mysqli $connection Objeto de conexion con la base de datos
 */

include_once 'util/jwt.php';

class StudentDAO {
    private $host = 'localhost';
    private $user = 'root';
    private $password = '12345';
    private $dbName = 'unah_registration';
    private $connection;

    /**
     * Constructor de clase donde se hace la conexion con la base de datos.
     * 
     * @return mysqli $connection Objeto mysqli que contiene la conexion con la base de datos, o valor null en caso de fallo.
     */
    public function __construct () {
        try {
            $this->connection = new mysqli($this->host, $this->user, $this->password, $this->dbName);
        } catch (Exception $error) {
            printf("Failed connection: %s\n", $error->getMessage());
        }   
    }

    /**
     * Metodo para la autenticacion de usuarios estudiantes.
     * 
     * @param string $username Nombre de usuario (numero de cuenta) del estudiante.
     * @param string $password Contrasena del usuario estudiante.
     * 
     * @return array $response Arreglo asociativo con el resultado de la autenticacion (success), un mensaje de retroalimentacion (message), el valor del token (nulo en caso de fallo) y el tipo de usuario en caso de exito (rol del usuario).
     * 
     * @author @AngelNolasco
     * @created 03/12/2024
     */
    public function authStudent (string $username, string $password) {
        if (isset($username) && isset($password)) {
            //Busqueda del usuario en la base de datos
            $query = "SELECT id_user_student, password_user_student FROM `UsersStudents` WHERE username_user_student = ?;";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) { //Se encontro un usuario con ese username
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $auxID = intval($row['id_user_student']);
                $hashPassword = $row['password_user_student'];
                $coincidence = Encryption::verifyPassword($password, $hashPassword);

                if ($coincidence) { //Si la contrasena ingresada coincide con la de la BD
                    //Consulta para obtener los accesos del usuario
                    $queryAccessArray = "SELECT `AccessControl`.id_access_control FROM `AccessControl`
                    INNER JOIN `AccessControlRoles` ON `AccessControl`.id_access_control = `AccessControlRoles`.id_access_control
                    INNER JOIN `RolesUsersStudent` ON `AccessControlRoles`.id_role = `RolesUsersStudent`.id_role_student
                    INNER JOIN `UsersStudents` ON `RolesUsersStudent`.id_user_student = `UsersStudents`.id_user_student
                    WHERE `UsersStudents`.id_user_student = ?;";
                    $stmtAccessArray = $this->connection->prepare($queryAccessArray);
                    $stmtAccessArray->bind_param('i', $auxID);
                    $stmtAccessArray->execute();
                    $resultAccessArray = $stmtAccessArray->get_result();

                    $accessArray = [];
                    while ($rowAccess = $resultAccessArray->fetch_array(MYSQLI_ASSOC)) {
                        $accessArray[] = $rowAccess['id_access_control'];
                    }
                    $resultAccessArray->free();
                    $stmtAccessArray->close();

                    //Liberacion del resultado de la consulta
                    while ($this->connection->more_results() && $this->connection->next_result()) {
                        $extraResult = $this->connection->store_result();
                        if ($extraResult) {
                            $extraResult->free();
                        }
                    }

                    //Creacion del payload con el username y el arreglo de accesos del usuario administrador de admisiones
                    $payload = [
                        'userAdmissionAdmin' => $username,
                        'accessArray' => $accessArray
                    ];
                    $newToken = JWT::generateToken($payload); //Generacion del token a partir del payload
                    
                    //Consulta de busqueda del usuario en la tabla relacional de tokens respectiva
                    $queryCheck = "SELECT id_token_user_student FROM `TokenUserStudent` WHERE id_user_student = ?;";
                    $stmtCheck = $this->connection->prepare($queryCheck);
                    $stmtCheck->bind_param('i', $auxID);
                    $stmtCheck->execute();
                    $resultCheck = $stmtCheck->get_result();

                    if ($resultCheck->num_rows > 0) { //El usuario existe, se actualiza el token
                        $queryUpdate = "UPDATE `TokenUserStudent` SET token_student = ? WHERE id_user_student = ?;";
                        $stmtUpdate = $this->connection->prepare($queryUpdate);
                        $stmtUpdate->bind_param('si', $newToken, $auxID);
                        $resultUpdate = $stmtUpdate->execute();
                        
                        if ($resultUpdate === false) { //Si la actualizacion falla
                            return $response = [
                                'success' => false,
                                'message' => 'Token no actualizado.',
                                'token' => null
                            ];
                        }
                        $stmtUpdate->close();
                    } else { //El usuario no existe en la tabla asi que se inserta
                        $queryInsert = "INSERT INTO `TokenUserStudent` (id_user_student, token_student) VALUES (?, ?);";
                        $stmtInsert = $this->connection->prepare($queryInsert);
                        $stmtInsert->bind_param('is', $auxID, $newToken);
                        $resultInsert = $stmtInsert->execute();

                        if ($resultInsert === false) { //Si la insercion falla
                            return $response = [
                                'success' => false,
                                'message' => 'Token no insertado.',
                                'token' => null
                            ];
                        }
                        $stmtInsert->close();
                    }

                    return $response = [ //Si todo funciona se retorna un arreglo asociativo donde va el token
                        'success' => true,
                        'message' => 'Validacion de credenciales exitosa.',
                        'token' => $newToken,
                        'typeUser' => 15
                    ];

                } else { //La contrasena no coincide
                    return $response = [
                        'success' => false,
                        'message' => 'Credenciales invalidas.',
                        'token' => null
                    ];
                }

            } else { //No existe usuario con el username ingresado
                return $response = [
                    'success' => false,
                    'message' => 'Usuario y/o contrasena incorrectos.',
                    'token' => null
                ];
            }

        } else { //Credenciales nulas
            return $response = [
                'success' => false,
                'message' => 'Credenciales invalidas.',
                'token' => null
            ];
        }
    }

    /**
     * 
     */
    public function getEnrollmentClassSection (string $idStudent) {
        if (!isset($idStudent)) {
            return $response = [
                'status' => 'warning',
                'message' => 'Numero de cuenta no definido o nulo.'
            ];
        }

        $queryEnrollmentClassSection = "CALL SP_GET_ENROLLMENT_CLASS_SECTION_BY_STUDENT(?);";
        $stmtEnrollmentClassSection = $this->connection->prepare($queryEnrollmentClassSection);
        $stmtEnrollmentClassSection->bind_param('s', $idStudent);
        $stmtEnrollmentClassSection->execute();
        $resultEnrollmentClassSection = $stmtEnrollmentClassSection->get_result();

        if ($resultEnrollmentClassSection->num_rows > 0) {
            $groupedClasses = [];

            while ($row = $resultEnrollmentClassSection->fetch_array(MYSQLI_ASSOC)) {
                $sectionCode = $row['section_code'];

                if (!isset($groupedClasses[$sectionCode])) {
                    $groupedClasses[$sectionCode] = [
                        'class_code' => $row['class_code'],
                        'name_class' => $row['name_class'],
                        'section_code' => $row['section_code'],
                        'section_days' => [],
                        'hi' => $row['hi'],
                        'hf' => $row['hf'],
                        'name_classroom' => $row['name_classroom'],
                        'professor_name' =>$row['professor_name']
                    ];
                }

                $groupedClasses[$sectionCode]['section_days'][] = $row['section_day'];
            }

            foreach ($groupedClasses as &$class) {
                $class['section_days'] = implode(', ', $class['section_days']);
            }

            return $response = [
                'status' => 'success',
                'message' => 'Consulta de clases exitosa.',
                'enrollmentClassSections' => array_values($groupedClasses)
            ];
        } else {
            return $response = [
                'status' => 'info',
                'message' => 'No se encontraron secciones de clases activas en las que el estudiante este matriculado.'
            ];
        }
    }

    /**
     * 
     */
    public function createRequestExcepcionalCancellation (string $idStudent, $reasons, $document, $evidence, $idsClassSections) {
        if (!(isset($idRequest) && isset($reasons) && isset($document) && isset($idsClassSections)) || empty($idsClassSections)) {
            return $response = [
                'success' => false,
                'message' => 'Hay datos nulos que necesitan un valor obligatoriamente o no hay secciones definidas.'
            ];
        }
        
        $queryInsertRequest = "INSERT INTO `RequestsCancellationExceptionalClasses` (id_student, reasons_request_cancellation_exceptional_classes, document_request_cancellation_exceptional_classes, evidence_request_cancellation_exceptional_classes, status_request_cancellation_exceptional_classes)
        VALUES (?, ?, ?, ?, TRUE);";
        $stmtInsertRequest = $this->connection->prepare($queryInsertRequest);
        $stmtInsertRequest->bind_param('ssss', $idStudent, $reasons, $document, $evidence);
        
        if ($stmtInsertRequest->execute()) {
            $idRequest = $this->connection->insert_id;
            $errors = [];
            
            foreach ($idsClassSections as $idSection) {
                $queryInsertListClassSection = "INSERT INTO `ListClassSectionCancellationExceptional` (id_class_section, id_requests_cancellation_exceptional_classes)
                VALUES (?, ?);";
                $stmtInsertListClassSection = $this->connection->prepare($queryInsertListClassSection);
                $stmtInsertListClassSection->bind_param('ii', $idSection, $idRequest);

                if (!($stmtInsertListClassSection->execute())) {
                    $errors [] = 'No se registro la seccion con ID '.$idSection.' en la solicitud.';
                }
            }

            return $response = [
                'success' => true,
                'message' => 'Registro de solicitud finalizado.',
                'errors' => $errors
            ];
        } else {
            return $response = [
                'success' => false,
                'message' => 'No se pudo registrar la solicitud.'
            ];
        }
    }
}
?>