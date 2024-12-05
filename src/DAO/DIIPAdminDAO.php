<?php
include_once 'util/jwt.php';
include_once 'util/Code.php';
include_once 'util/StudentFunctions.php';
include_once 'util/encryption.php';

/**
 * Clase objeto de acceso a datos y controlador de administrador de DIPP
 * 
 * @property string $host Direccion de host de base de datos
 * @property string $user Usuario de acceso a la base de datos
 * @property string $password Clave de acceso del respectivo usuario
 * @property string $dbName Nombre de la base de datos
 * @property mysqli $connection Objeto de conexion con la base de datos
 */

class DIIPAdminDAO {
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
     * 
     */
    public function authDIIPAdmin (string $username, $password) {
        if (isset($username) && isset($password)) {
            //Busqueda del usuario en la BD
            $querySelectUserDIIP = "SELECT id_user_registry_administrator, password_user_registry_administrator FROM `UsersRegistryAdministrator` WHERE username_user_registry_administrator = ?;";
            $stmtSelectUserDIIP = $this->connection->prepare($querySelectUserDIIP);
            $stmtSelectUserDIIP->bind_param('s', $username);
            $stmtSelectUserDIIP->execute();
            $resultSelectUserDIIP = $stmtSelectUserDIIP->get_result();

            if ($resultSelectUserDIIP->num_rows > 0) {
                $row = $resultSelectUserDIIP->fetch_array(MYSQLI_ASSOC);
                $idUserDIIP = $row['id_user_registry_administrator'];
                $hashPassword = $row['password_user_registry_administrator'];
                $coincidence = Encryption::verifyPassword($password, $hashPassword);

                if ($coincidence) {
                    $queryAccessArray = "SELECT `AccessControl`.id_access_control FROM `AccessControl`
                    INNER JOIN `AccessControlRoles` ON `AccessControl`.id_access_control = `AccessControlRoles`.id_access_control
                    INNER JOIN `RolesUsersRegistryAdministrator` ON `AccessControlRoles`.id_role = `RolesUsersRegistryAdministrator`.id_role_registry_administrator
                    INNER JOIN `UsersRegistryAdministrator` ON `RolesUsersRegistryAdministrator`.id_user_registry_administrator = `UsersRegistryAdministrator`.id_user_registry_administrator
                    WHERE `UsersRegistryAdministrator`.id_user_registry_administrator = ?;";
                    $stmtAccessArray = $this->connection->prepare($queryAccessArray);
                    $stmtAccessArray->bind_param('s', $idUserDIIP);
                    $stmtAccessArray->execute();
                    $resultAccessArray = $stmtAccessArray->get_result();

                    $accessArray = [];
                    while ($rowAccess = $resultAccessArray->fetch_array(MYSQLI_ASSOC)) {
                        $accessArray[] = $rowAccess['id_access_control'];
                    }
                    $resultAccessArray->free();
                    $stmtAccessArray->close();

                    while ($this->connection->more_results() && $this->connection->next_result()) {
                        $extraResult = $this->connection->store_result();
                        if ($extraResult) {
                            $extraResult->free();
                        }
                    }

                    $payload = [
                        'userDIIPAdmin' => $username,
                        'accessArray' => $accessArray
                    ];
                    $newToken = JWT::generateToken($payload);

                    $queryCheck = "SELECT id_user_registry_administrator FROM `TokenUserRegistryAdministrator` WHERE id_user_registry_administrator = ?;";
                    $stmtCheck = $this->connection->prepare($queryCheck);
                    $stmtCheck->bind_param('i', $idUserDIIP);
                    $stmtCheck->execute();
                    $resultCheck = $stmtCheck->get_result();

                    if ($resultCheck->num_rows > 0) { //Usuario encontrado, se actualiza su token
                        $queryUpdate = "UPDATE `TokenUserRegistryAdministrator` SET token_registry_administrator = ? WHERE id_user_registry_administrator = ?;";
                        $stmtUpdate = $this->connection->prepare($queryUpdate);
                        $stmtUpdate->bind_param('si', $newToken, $idUserDIIP);
                        $resultUpdate = $stmtUpdate->execute();
                        
                        if ($resultUpdate === false) { //Si la actualizacion falla
                            return $response = [
                                'success' => false,
                                'message' => 'Token no actualizado.',
                                'token' => null
                            ];
                        }
                        $stmtUpdate->close();
                    } else { //Usuario no encontrado, se registra
                        $queryInsert = "INSERT INTO `TokenUserRegistryAdministrator` (id_user_registry_administrator, token_registry_administrator) VALUES (?, ?);";
                        $stmtInsert = $this->connection->prepare($queryInsert);
                        $stmtInsert->bind_param('is', $idUserDIIP, $newToken);
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
                        'typeUser' => 'dippAdmin'
                    ];
                    
                } else { //Contrasena no coincide
                    return $response = [
                        'success' => false,
                        'message' => 'Usuario y/o contrasena incorrectos.',
                        'token' => null
                    ];
                }

            } else { //Usuario no encontrado
                return $response = [
                    'success' => false,
                    'message' => 'Usuario y/o contrasena incorrectos.',
                    'token' => null
                ];
            }
        } else { //Al menos uno de los parametros es nulo
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
    public function insertStudentsByCSV ($csvFile, bool $firstRowHeaders=true) {
        $fileTempPath = $csvFile['tmp_csv'];
        
        if(($handle = fopen($fileTempPath, 'r')) !== FALSE) {
            $rowsInsertedStudent = 0;
            $rowsInsertedStudentRegionalCenter = 0;
            $rowsInsertedStudentUndergraduate = 0;
            $rowsInsertedStudentProfile = 0;
            $rowsInsertUserStudent = 0;
            $rowsInsertRolUserStudent = 0;
            $errors = [];
            $headers = ["id_card_student", "first_name", "second_name", "third_name", "first_lastname", "second_lastname", "address", "email", "phone_number", "id_regional_center", "name_undergraduate"];

            while (($row = fgetcsv($handle, 0, ',')) !== FALSE) {
                if ($firstRowHeaders) {
                    if($row == $headers) {
                        $firstRowHeaders = false;
                        continue;
                    } else { //Las cabeceras del CSV son las incorrectas
                        $errors[] = "Error en la lectura de las cabeceras.";
                        return $response = [
                            'success' => false,
                            'message' => 'Cabeceras de CSV erroneas.',
                            'errors' => $errors
                        ];
                        break;
                    }
                }

                //Escapar los valores para prevenir inyecciones SQL
                $idCardStudent = $this->connection->real_escape_string($row[0]);
                $firstName = $this->connection->real_escape_string($row[1]);
                $secondName = $this->connection->real_escape_string($row[2]);
                $thirdName = $this->connection->real_escape_string($row[3]);
                $firstLastname = $this->connection->real_escape_string($row[4]);
                $secondLastname = $this->connection->real_escape_string($row[5]);
                $adress = $this->connection->real_escape_string($row[6]);
                $personalEmail = $this->connection->real_escape_string($row[7]);
                $phoneNumber = $this->connection->real_escape_string($row[8]);
                $nameRegionalCenter = $this->connection->real_escape_string($row[9]);
                $nameUndergraduate = $this->connection->real_escape_string($row[10]);

                $idRegionalCenter = $this->getIdRegionalCenterByName($nameRegionalCenter);

                if (!($idRegionalCenter['success'])) {
                    $this->connection->rollback();
                    return $response = [
                        'success' => false,
                        'message' => 'Centro regional no encontrado.'
                    ];
                }

                $accountNumberStudent = StudentFunctions::generateAccountNumber($idRegionalCenter['idRegionalCenter']); //Generacion del numero de cuenta
                $institutionalEmail = StudentFunctions::generateEmail($personalEmail); //Generacion de correo institucional

                //INSERCION ESTUDIANTE
                $queryInsertStudent = "INSERT INTO `Students` (id_student, institutional_email_student, id_card_student, first_name_student, second_name_student, third_name_student, first_lastname_student, second_lastname_student, address_student, email_student, phone_number_student, status_student) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE);";
                $stmtInsertStudent = $this->connection->prepare($queryInsertStudent);
                $stmtInsertStudent->bind_param('sssssssssss', $accountNumberStudent, $institutionalEmail, $idCardStudent, $firstName, $secondName, $thirdName, $firstLastname, $secondLastname, $adress, $personalEmail, $phoneNumber);
                
                if($stmtInsertStudent->execute()) {
                    $rowsInsertedStudent++;
                } else {
                    $errors[] = "Fallo en la insercion del estudiante con numero de identidad: " . $idCardStudent;
                }

                while ($this->connection->more_results() && $this->connection->next_result()) {
                    $extraResult = $this->connection->store_result();
                    if ($extraResult) {
                        $extraResult->free();
                    }
                }

                //INSERCION ESTUDIANTE-CENTRO_REGIONAL
                $queryInsertStudentRegionalCenter = "INSERT INTO `StudentsRegionalCenters` (id_student, id_regional_center, status_regional_center_student)
                VALUES (?, ?, TRUE);";
                $stmtInsertStudentRegionalCenter = $this->connection->prepare($queryInsertStudentRegionalCenter);
                $stmtInsertStudentRegionalCenter->bind_param('si', $accountNumberStudent, $idRegionalCenter['idRegionalCenter']);
                if($stmtInsertStudentRegionalCenter->execute()) {
                    $rowsInsertedStudentRegionalCenter++;
                } else {
                    $errors[] = "Fallo en la insercion estudiante-centro_regional, con numero de identidad del estudiante: " . $idCardStudent;
                }

                while ($this->connection->more_results() && $this->connection->next_result()) {
                    $extraResult = $this->connection->store_result();
                    if ($extraResult) {
                        $extraResult->free();
                    }
                }

                //INSERCION ESTUDIANTE-PREGRADO
                $idUndergraduate = $this->getIdUndergraduteByName($nameUndergraduate); //Obtencion del ID de la carrera de pregrado

                if ($idUndergraduate['success']) {
                    $queryInsertStudentUndergraduate = "INSERT INTO `StudentsUndergraduates` (id_student, id_undergraduate, status_student_undergraduate) VALUES (?, ?, TRUE);";
                    $stmtInsertStudentUndergraduate = $this->connection->prepare($queryInsertStudentUndergraduate);
                    $stmtInsertStudentUndergraduate->bind_param('si', $accountNumberStudent, $idUndergraduate['idUndergraduate']);
    
                    if($stmtInsertStudentUndergraduate->execute()) {
                        $rowsInsertedStudentUndergraduate++;
                    } else {
                        $errors[] = "Fallo en la insercion estudiante-pregrado, con numero de identidad del estudiante: " . $idCardStudent;
                    }
                } else {
                    $errors[] = "Fallo en la insercion estudiante-pregrado, con numero de identidad del estudiante: " . $idCardStudent . ". Carrera no encontrada.";
                }

                while ($this->connection->more_results() && $this->connection->next_result()) {
                    $extraResult = $this->connection->store_result();
                    if ($extraResult) {
                        $extraResult->free();
                    }
                }

                //INSERCION ESTUDIANTE-PERFIL_ESTUDIANTE
                $queryStudentProfile = "INSERT INTO StudentProfile (id_student, first_student_profile_picture, second_student_profile_picture, third_student_profile_picture, student_personal_description, status_student_profile) VALUES (?, null, null, null, null, TRUE);";
                $stmtStudentProfile = $this->connection->prepare($queryStudentProfile);
                $stmtStudentProfile->bind_param('s', $accountNumberStudent);

                if($stmtStudentProfile->execute()) {
                    $rowsInsertedStudentProfile++;
                } else {
                    $errors[] = "Fallo en la insercion estudiante-perfil, con numero de identidad del estudiante: " . $idCardStudent;
                }

                while ($this->connection->more_results() && $this->connection->next_result()) {
                    $extraResult = $this->connection->store_result();
                    if ($extraResult) {
                        $extraResult->free();
                    }
                }

                //INSERCION USUARIO_ESTUDIANTE
                $randomPassword = Password::generatePassword(); //Generacion de contrasena aleatoria
                $hashPassword = Encryption::hashPassword($randomPassword); //Cifrado de contrasena

                $queryInsertUserStudent = "INSERT INTO `UsersStudents` (username_user_student, password_user_student, status_user_student) VALUES (?, ?, TRUE);";
                $stmtInsertUserStudent = $this->connection->prepare($queryInsertUserStudent);
                $stmtInsertUserStudent->bind_param('ss', $accountNumberStudent, $hashPassword);

                if($stmtInsertUserStudent->execute()) {
                    $rowsInsertUserStudent++;
                } else {
                    $errors[] = "Fallo en la insercion estudiante-perfil, con numero de identidad del estudiante: " . $idCardStudent;
                }

                while ($this->connection->more_results() && $this->connection->next_result()) {
                    $extraResult = $this->connection->store_result();
                    if ($extraResult) {
                        $extraResult->free();
                    }
                }

                //INSERCION ROL-USUARIO_ESTUDIANTE
                $idRole = 0;
                $querySelectIdRole = "SELECT id_role FROM Roles WHERE role = 'Student';";
                $resultSelectIdRole = $this->connection->execute_query($querySelectIdRole);
                while ($row = $resultSelectIdRole->fetch_array(MYSQLI_ASSOC)) {
                    $idRole = intval($row['id_role']);
                }

                $idUser = 0;
                $querySelectIdUser = "SELECT id_user_student FROM UsersStudents WHERE username_user_student = ?";
                $resultSelectIdUser = $this->connection->execute_query($querySelectIdUser, [$accountNumberStudent]);
                while ($row = $resultSelectIdUser->fetch_array(MYSQLI_ASSOC)) {
                    $idUser = intval($row['id_user_student']);
                }

                $queryInsertRolUserStudent = "INSERT INTO RolesUsersStudent (id_user_student, id_role_student, status_role_student) VALUES (?, ?, TRUE);"; 
                $stmtInsertRolUserStudent = $this->connection->prepare($queryInsertRolUserStudent);
                $stmtInsertRolUserStudent->bind_param('si', $idUser, $idRole);

                if($stmtInsertRolUserStudent->execute()) {
                    $rowsInsertRolUserStudent++;
                } else {
                    $errors[] = "Fallo en la insercion rol-usuario_estudiante, con numero de identidad del estudiante: " . $idCardStudent;
                }
            }

            return $response = [
                'success' => true,
                'message' => 'Inserciones finalizadas.',
                'passwordUser' => $randomPassword,
                'errors' => $errors,
                'rows' => [
                    'Total estudiantes registrados: '.$rowsInsertedStudent,
                    'Total filas estudiante-centro_regional registradas: '.$rowsInsertedStudentRegionalCenter,
                    'Total filas estudiantes-pregrado registradas: '.$rowsInsertedStudentUndergraduate,
                    'Total perfiles de estudiantes creados: '.$rowsInsertedStudentProfile,
                    'Total usuarios estudiantes creados: '.$rowsInsertUserStudent,
                    'Total filas usuario-rol registradas: '.$rowsInsertRolUserStudent
                ]
            ];

        } else {
            return $response = [
                'success' => false,
                'message' => 'Error al abrir el archivo CSV.'
            ];
        }
    }

    /**
     * Metodo auxiliar para obtener el ID de una carrera de pregrado a traves de su nombre. Usado en metodo insertStudentsByCSV.
     * 
     * @param string $nameUndergraduate Nombre de la carrera de pregrado.
     * 
     * @return array Arreglo asociativo con resultado de la consulta (success), mensaje de retroalimentacion (message) e ID de la carrera (en caso de exito).
     */
    public function getIdUndergraduteByName (string $nameUndergraduate) {
        $querySelectIdUndergraduate = "SELECT id_undergraduate FROM `Undergraduates` WHERE name_undergraduate = ?;";
        $resultSelectIdUndergraduate = $this->connection->execute_query($querySelectIdUndergraduate, [$nameUndergraduate]);

        if ($resultSelectIdUndergraduate->num_rows > 0) {
            $row = $resultSelectIdUndergraduate->fetch_assoc();
            $idUndergraduate = $row['id_undergraduate'];

            return [
                'success' => true,
                'message' => 'Carrera de pregrado encontrada.',
                'idUndergraduate' => $idUndergraduate 
            ];
        } else{
            return [
                'success' => false,
                'message' => 'Carrera de pregrado no encontrada.'
            ];
        }
    }

    public function getIdRegionalCenterByName (string $regionalCenterName) {
        if (isset($regionalCenterName)) {
            $query = "SELECT id_regional_center FROM RegionalCenters WHERE name_regional_center = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $regionalCenterName);

            if($stmt->execute()) {
                $idRegionalCenter = 0;
                $result = $stmt->get_result();
                while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    $idRegionalCenter = $row['id_regional_center'];
                }

                return [
                    'success' => true,
                    'message' => 'Carrera de pregrado encontrada.',
                    'idRegionalCenter' => $idRegionalCenter 
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Centro regional no encontrado.'
            ];
        }
    }

}

?>