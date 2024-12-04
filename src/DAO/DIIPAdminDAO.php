<?php
include_once 'util/jwt.php';
include_once 'util/Code.php';
include_once 'util/StudentFunctions.php';

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
                $idRegionalCenter = intval($this->connection->real_escape_string($row[9]));
                $nameUndergraduate = $this->connection->real_escape_string($row[10]);

                $accountNumberStudent = StudentFunctions::generateAccountNumber($idRegionalCenter); //Generacion del numero de cuenta
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

                //INSERCION ESTUDIANTE-CENTRO_REGIONAL
                $queryInsertStudentRegionalCenter = "INSERT INTO StudentsRegionalCenters (id_student, id_regional_center, status_regional_center_student) VALUES (?, ?, TRUE);";
                $stmtInsertStudentRegionalCenter = $this->connection->prepare($queryInsertStudentRegionalCenter);
                $stmtInsertStudentRegionalCenter->bind_param('si', $accountNumberStudent, $idRegionalCenter);
                if($stmtInsertStudent->execute()) {
                    $rowsInsertedStudentRegionalCenter++;
                } else {
                    $errors[] = "Fallo en la insercion estudiante-centro_regional, con numero de identidad del estudiante: " . $idCardStudent;
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


                //INSERCION ESTUDIANTE-PERFIL_ESTUDIANTE
                $queryStudentProfile = "INSERT INTO StudentProfile (id_student, first_student_profile_picture, second_student_profile_picture, third_student_profile_picture, student_personal_description, status_student_profile) VALUES (?, null, null, null, null, TRUE);";
                $stmtStudentProfile = $this->connection->prepare($queryStudentProfile);
                $stmtStudentProfile->bind_param('s', $accountNumberStudent);

                if($stmtStudentProfile->execute()) {
                    $rowsInsertedStudentProfile++;
                } else {
                    $errors[] = "Fallo en la insercion estudiante-perfil, con numero de identidad del estudiante: " . $idCardStudent;
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

                //INSERCION ROL-USUARIO_ESTUDIANTE
                //======================== NUMERO FIJO DEL ROL DE ESTUDIANTE COMUN ========================
                $queryInsertRolUserStudent = "INSERT INTO RolesUsersStudent (id_user_student, id_role_student, status_role_student) VALUES (?, 14, TRUE);"; 
                $stmtInsertRolUserStudent = $this->connection->prepare($queryInsertRolUserStudent);
                $stmtInsertRolUserStudent->bind_param('s', $accountNumberStudent);

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
                'errors' => $errors
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

}

?>