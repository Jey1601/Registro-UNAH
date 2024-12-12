<?php
include_once 'util/jwt.php';
include_once 'util/StudentFunctions.php';
include_once 'util/encryption.php';
require_once 'util/mail.php';
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
     * Metodo para la autenticacion de un usuario administrador de registro (DIIP).
     * 
     * @param string $username Nombre de usuario (numero de cuenta de empleado) del administrador de registro ingresado en el login.
     * @param string $password Contresena del usuario administrador ingresada en el login.
     * 
     * @return array $response Arreglo asociativo que indica el resultado de la autenticacion (success) junto a un mensaje de retroalimentacion (message) y el valor del token generado (nulo en caso de fallo en la autenticacion).
     * 
     * @author @AngelNolasco
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
                        'typeUser' => 7
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
     * Metodo para el registro masivo de estudiantes a traves de un archivo CSV.
     * 
     * @param $csvFile Archivo CSV que contiene los datos necesarios de los estudiantes para registrarlos correctamente en la base de datos.
     * @param bool $firstRowHeaders Booleano para indicar si el archivo CSV trae cabeceras, verdadero (true) por defecto.
     * 
     * @return array $response Arreglo asociativo que indica el resultado del registro de los estudiantes (success), junto con un mensaje de retroalimentacion (message) y otro arreglo asociativo en el que se guardan los posibles errores de insercion (errors). Tambien se retorna la cantidad de registros hechos en cada tabla modificada, excepto en caso de errores mayores como los descritos a continuacion:
     *      En caso de que se indique que el CSV tiene cabeceras y no sean las correctas (array $headers), se retorna directamente el fallo.
     *      En caso de que el estudiante no se inserte en la tabla Students correctamente se retorna el fallo directamente y se hace un rollback de la base de datos.
     * 
     * @author @AngelNolasco
     * @created 04/12/2024
     */
    public function insertStudentsByCSV ($csvFile, bool $firstRowHeaders=true) {
        $fileTempPath = $csvFile['tmp_csv'];
        //Nos permite enviar los correos
        $mail = new mail();
        if(($handle = fopen($fileTempPath, 'r')) !== FALSE) {
            $rowsInsertedStudent = 0;
            $rowsInsertedStudentRegionalCenter = 0;
            $rowsInsertedStudentUndergraduate = 0;
            $rowsInsertedStudentProfile = 0;
            $rowsInsertUserStudent = 0;
            $rowsInsertRolUserStudent = 0;
            $rowsInsertAverage = 0;
            $rowsStudentClassStatus = 0;
            $errors = [];
            $headers = ["nombre_completo_apirante_admitido", "identidad_aspirante_admitido", "direccion_aspirante_admitido", "celular_aspirante_admitido","correo_personal_aspirante_admitido", "carrera_aspirante_admitido", "centro_regional_aspirante_admitido"];

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
                $fullName = $this->connection->real_escape_string($row[0]);
                $idCardStudent = $this->connection->real_escape_string($row[1]);
                $address = $this->connection->real_escape_string($row[2]);
                $phoneNumber = $this->connection->real_escape_string($row[3]);
                $personalEmail = $this->connection->real_escape_string($row[4]);
                $idUndergraduate = intval($this->connection->real_escape_string($row[5]));
                $idRegionalCenter = intval($this->connection->real_escape_string($row[6]));

                $partes = explode(" ", $fullName);

                // Inicializa las variables
                $firstName = $secondName = $thirdName = $firstLastname = $secondLastname = '';

                // Asigna las partes a las variables correspondientes
                if (count($partes) === 2) {
                    [$firstName, $firstLastname] = $partes;
                } elseif (count($partes) === 3) {
                    [$firstName, $secondName, $firstLastname] = $partes;
                } elseif (count($partes) === 4) {
                    [$firstName, $secondName, $firstLastname, $secondLastname] = $partes;
                } elseif (count($partes) === 5) {
                    [$firstName, $secondName, $thirdName, $firstLastname, $secondLastname] = $partes;
                }
                
                $accountNumberStudent = StudentFunctions::generateAccountNumber($idRegionalCenter); //Generacion del numero de cuenta
                $institutionalEmail = StudentFunctions::generateEmail($personalEmail); //Generacion de correo institucional

                //INSERCION ESTUDIANTE
                $queryInsertStudent = "INSERT INTO `Students` (id_student, institutional_email_student, id_card_student, first_name_student, second_name_student, third_name_student, first_lastname_student, second_lastname_student, address_student, email_student, phone_number_student, status_student) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE);";
                $stmtInsertStudent = $this->connection->prepare($queryInsertStudent);
                $stmtInsertStudent->bind_param('sssssssssss', $accountNumberStudent, $institutionalEmail, $idCardStudent, $firstName, $secondName, $thirdName, $firstLastname, $secondLastname, $address, $personalEmail, $phoneNumber);
                
                if($stmtInsertStudent->execute()) {
                    $rowsInsertedStudent++;
                } else {
                    $errors[] = "Fallo en la insercion del estudiante con numero de identidad: " . $idCardStudent;
                    $this->connection->rollback();
                    return [
                        'success' => false,
                        'message' => 'Estudiante no insertado.',
                        'errors' => $errors
                    ];
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
                $stmtInsertStudentRegionalCenter->bind_param('si', $accountNumberStudent, $idRegionalCenter);
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
                $queryInsertStudentUndergraduate = "INSERT INTO `StudentsUndergraduates` (id_student, id_undergraduate, status_student_undergraduate) VALUES (?, ?, TRUE);";
                $stmtInsertStudentUndergraduate = $this->connection->prepare($queryInsertStudentUndergraduate);
                $stmtInsertStudentUndergraduate->bind_param('si', $accountNumberStudent, $idUndergraduate);
    
                if($stmtInsertStudentUndergraduate->execute()) {
                    $rowsInsertedStudentUndergraduate++;

                    //Enviar el correo con usuario y contraseña

                    //INSERCION Y DEFINICION DE ESTADO ESTUDIANTE-CLASE_PREGRADO
                    //Obtencion de los IDs de las clases que pertenecen a la carrera a la que pertenece el estudiante
                    $querySelectIdsClasses = "SELECT id_class FROM `UndergraduateClass` WHERE id_undergraduate = ?;";
                    $resultSelectIdClasses = $this->connection->execute_query($querySelectIdsClasses, [$idUndergraduate]);

                    $idClasses = [];
                    if ($resultSelectIdClasses) {
                        while($row = $resultSelectIdClasses->fetch_assoc()) {
                            $idClasses[] = $row['id_class'];
                        }

                        while ($this->connection->more_results() && $this->connection->next_result()) {
                            $extraResult = $this->connection->store_result();
                            if ($extraResult) {
                                $extraResult->free();
                            }
                        }
                        
                        //Definicion StudentClassStatus
                        $queryInsertStudentClassStatus = "INSERT INTO `StudentClassStatus` (id_student, id_class, class_status) VALUES (?, ?, TRUE);";
                        foreach ($idClasses as $idClass) {
                            if (!isset($idClass)) {
                                $errors[] = "No se pudo obtener el codigo de una clase.";
                                continue;
                            }

                            $resultInsertStudentClassStatus = $this->connection->execute_query($queryInsertStudentClassStatus, [$accountNumberStudent, $idClass]);
                            
                            if (!$resultInsertStudentClassStatus) {
                                $this->connection->rollback();
                                $errors[] =  "No se pudo definir el estado de la relacion estudiante-clase del estudiante con numero de identidad: " . $idCardStudent." en la clase con codigo: ".$idClass;
                                continue;
                            }

                            $rowsStudentClassStatus++;

                            while ($this->connection->more_results() && $this->connection->next_result()) {
                                $extraResult = $this->connection->store_result();
                                if ($extraResult) {
                                    $extraResult->free();
                                }
                            }
                        }

                    } else {
                        $errors = "No se pudieron identificar las clases que tiene que cursar el estudiante con numero de identidad: " . $idCardStudent;
                    }

                } else {
                    $errors[] = "Fallo en la insercion estudiante-pregrado, con numero de identidad del estudiante: " . $idCardStudent;
                    $errors[] = "No se pudo definir el estado de la relacion estudiante-clase con las clases del estudiante con numero de identidad: " . $idCardStudent;
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
                    
                    $mail -> sendStudentsLogin($fullName, $accountNumberStudent, $randomPassword, $personalEmail);
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

                //INSERCION DE LOS PROMEDIOS DEL ESTUDIANTE
                $queryInsertAverage = "INSERT INTO `StudentGradesAverages` (id_student, period_grade_average_student, annual_academic_grade_average_student, global_grade_average_student) VALUES (?, 0, 0, 99.99);";
                $resultInsertAverage = $this->connection->execute_query($queryInsertAverage, [$accountNumberStudent]);

                if ($resultInsertAverage) {
                    $rowsInsertAverage++;
                } else {
                    $errors[] = "Fallo en la definicion de los promedios para el estudiante con numero de identidad: " . $idCardStudent;
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
                    'Total filas usuario-rol registradas: '.$rowsInsertRolUserStudent,
                    'Total de registros de promedios de estudiantes: '.$rowsInsertAverage++,
                    'Total de actualizacion de estado estudiante-clase: '.$rowsStudentClassStatus++
                ]
            ];
        } else {
            return $response = [
                'success' => false,
                'message' => 'Error al abrir el archivo CSV.'
            ];
        }
    }

}

?>