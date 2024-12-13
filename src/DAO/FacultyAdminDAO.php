<?php
include_once 'util/jwt.php';
include_once 'util/encryption.php';
//include_once 'util/Code.php';
require_once 'util/mail.php';

/**
 * Clase FacultyAdminDAO es un controlador y objeto de acceso a datos de los administradores de facultad.
 * 
 * @property string $host Direccion de host de base de datos
 * @property string $user Usuario de acceso a la base de datos
 * @property string $password Clave de acceso del respectivo usuario
 * @property string $dbName Nombre de la base de datos
 * @property mysqli $connection Objeto de conexion con la base de datos
 */
class FacultyAdminDAO {
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
     * Metodo para la autenticacion de usuarios administradores de facultad.
     * 
     * @param string $username Username de usuario administrador de facultad.
     * @param string $password Contrasena de usuario administrador de facultad.
     * 
     * @return array $response Arreglo asociativo que indica si la autenticacion fallo o no junto con un mensaje de retroalimentacion y el valor del token (nulo en caso de fallo de autenticacion).
     * 
     * @author @AngelNolasco
     * @created 02/12/2024
     */
    public function authFacultyAdmin(string $username, string $password) {
        if (isset($username) && isset($password)) {
            //Busqueda del usuario en la base de datos
            $query = "SELECT id_user_faculties_administrator, password_user_faculties_administrator FROM UsersFacultiesAdministrator WHERE username_user_faculties_administrator=?;";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) { //Se encontro un usuario con ese username
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $auxID = intval($row['id_user_faculties_administrator']);
                $hashPassword = $row['password_user_faculties_administrator'];
                $coincidence = Encryption::verifyPassword($password, $hashPassword);

                if ($coincidence) { //Si la contrasena ingresada coincide con la de la BD
                    //Consulta para obtener los accesos del usuario
                    $queryAccessArray = "SELECT  AccessControl.id_access_control, Faculties.id_faculty FROM AccessControl INNER JOIN AccessControlRoles ON AccessControl.id_access_control = AccessControlRoles.id_access_control INNER JOIN RolesUsersFacultiesAdministrator ON AccessControlRoles.id_role = RolesUsersFacultiesAdministrator.id_role_faculties_administrator INNER JOIN UsersFacultiesAdministrator ON RolesUsersFacultiesAdministrator.id_user_faculties_administrator = UsersFacultiesAdministrator.id_user_faculties_administrator INNER JOIN Faculties ON UsersFacultiesAdministrator.id_faculty = Faculties.id_faculty WHERE UsersFacultiesAdministrator.id_user_faculties_administrator = ?;";
                    $stmtAccessArray = $this->connection->prepare($queryAccessArray);
                    $stmtAccessArray->bind_param('i', $auxID);
                    $stmtAccessArray->execute();
                    $resultAccessArray = $stmtAccessArray->get_result();

                    $accessArray = [];
                    while ($rowAccess = $resultAccessArray->fetch_array(MYSQLI_ASSOC)) {
                        $accessArray[] = $rowAccess['id_access_control'];
                        $facultyID = $rowAccess['id_faculty'];
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

                    //Creacion del payload con el username y el arreglo de accesos del usuario administrador de facultad
                    $payload = [
                        'username' => $username,
                        'accessArray' => $accessArray,
                        'facultyID' => $facultyID
                    ];
                    $newToken = JWT::generateToken($payload); //Generacion del token a partir del payload
                    
                    //Consulta de busqueda del usuario en la tabla relacional de tokens respectiva
                    $queryCheck = "SELECT id_user_faculties_administrator FROM TokenUserFacultiesAdministrator WHERE id_user_faculties_administrator = ?;";
                    $stmtCheck = $this->connection->prepare($queryCheck);
                    $stmtCheck->bind_param('i', $auxID);
                    $stmtCheck->execute();
                    $resultCheck = $stmtCheck->get_result();

                    if ($resultCheck->num_rows > 0) { //El usuario existe, se actualiza el token
                        $queryUpdate = "UPDATE `TokenUserFacultiesAdministrator` SET token_faculties_administrator = ? WHERE id_user_faculties_administrator = ?;";
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
                        $queryInsert = "INSERT INTO `TokenUserFacultiesAdministrator` (id_user_faculties_administrator, token_faculties_administrator) VALUES (?, ?);";
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
                        'typeUser' => 8
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
     * Metodo para registrar un nuevo profesor en la base de datos.
     * 
     * @param string $firtsName Primer nombre del maestro.
     * @param string $secondName Segundo nombre del maestro.
     * @param string $thirdName Tercer nombre del maestro (puede ser nulo).
     * @param string $firstLastname Primer apellido del maestro.
     * @param string $secondLastname Segundo apellido del maestro (puede ser nulo).
     * @param string $image Cadena de bits de la imagen del perfil del maestro.
     * @param int $idProfessorObligation ID de la obligacion del maestro (numero maximo y minimo de UV que puede tener por periodo/semestre).
     * @param string $nameRegionalCenter Nombre del centro regional al que pertenece el maestro.
     * @param string $departmentName Nombre del departamento al que pertenece el maestro.
     * 
     * @return array $response Arrelgo asociativo con resultado de la insercion ('success') y mensaje de retroalimentacion ('message').
     *      PARAMETRO NO PERMITIDO COMO NULO SETEADO COMO NULO: 'success'=>false
     *      CENTRO REGIONAL NO ENCONTRADO EN LA BD: 'success'=>false
     *      FALLO EN LA INSERCION DEL PROFESOR: 'success'=>false
     *      FALLO EN LA INSERCION DEL USUARIO DEL PROFESOR: 'success'=>false
     *      FALLO EN LA INSERCION DE LA RELACION DEL PROFESOR CON UN DEPARTAMENTO: 'success'=>false
     *      SIN FALLOS PRODUCIDOS: 'success'=>true (Se retornan el nombre de usuario y la contrasena del usuario del profesor)
     * 
     * @author @AngelNolasco @JeysonEspinal
     * @created 02/12/2024
     */
    public function createProfessor (
        string $firstName, string $secondName, string $thirdName, string $firstLastname, string $secondLastname, string $email,
        string $image, int $idProfessorObligation, int $idRegionalCenter, int $idDepartment
    ) {
        if (!(
            isset($firstName) && isset($secondName) && isset($firstLastname)
            && isset($idProfessorObligation) && isset($idRegionalCenter)
        )) { //Hay un parametro nulo
            return $response = [
                'success' => false,
                'message' => 'Hay al menos un dato nulo.'
            ];
        }

        $errors = [];

        //INSERCION DE DOCENTE
        $queryInsertProfessor = "INSERT INTO `Professors` (first_name_professor, second_name_professor, third_name_professor, first_lastname_professor, second_lastname_professor, email_professor, picture_professor, id_professors_obligations, id_regional_center, status_professor) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE);";
        $stmtInsertProfessor = $this->connection->prepare($queryInsertProfessor);
        $stmtInsertProfessor->bind_param('sssssssii', $firstName, $secondName, $thirdName, $firstLastname, $secondLastname, $email, $image, $idProfessorObligation, $idRegionalCenter);

        if (!($stmtInsertProfessor->execute())) { //La insercion falla
            $this->connection->rollback();
            $stmtInsertProfessor->close();

            return $response = [
                'success' => false,
                'message' => 'Profesor no insertado.'
            ];
        }

        $queryIDProfessor = "SELECT id_professor FROM Professors WHERE email_professor = ?;";
        $stmtIDProfessor = $this->connection->prepare($queryIDProfessor);
        $stmtIDProfessor->bind_param('s', $email);
        $stmtIDProfessor->execute();
        $resultIDProfessor = $stmtIDProfessor->get_result();
        while ($rowIDProfessor = $resultIDProfessor->fetch_array(MYSQLI_ASSOC)) {
            $idProfessor = $rowIDProfessor['id_professor'];
            break;
        }

        //Liberacion del resultado de la consulta
        $resultIDProfessor->free();
        while ($this->connection->more_results() && $this->connection->next_result()) {
            $extraResult = $this->connection->store_result();
            if ($extraResult) {
                $extraResult->free();
            }
        }

        //INSERCION RELACION Docente-Usuario_Docente
        $passwordUser = Password::generatePassword();
        $idUserProfessor = $this->insertUserProfessor($idProfessor, $passwordUser);
        //Enviar correo aquí
        $mail = new mail();
        $mail->sendUserProfessor($firstName.$firstLastname,$idProfessor,$email,$passwordUser);

        if(!($idUserProfessor['success'])) {
            $errors[] = $idUserProfessor['message'];
        }

        //INSERCION RELACION Docente-Departamento
        $queryInsertProfessorDeparment = "INSERT INTO `ProfessorsDepartments` (id_department, id_professor, status_professor_department) VALUES (?, ?, TRUE);";
        $stmtInsertProfessorDepartment = $this->connection->prepare($queryInsertProfessorDeparment);
        $stmtInsertProfessorDepartment->bind_param('ii', $idDepartment, $idProfessor);
        
        if(!($stmtInsertProfessorDepartment->execute())) {
            $this->connection->rollback();
            $stmtInsertProfessorDepartment->close();
            $errors[] = 'Insercion Profesor-Departamento fallida.';
        }
        
        //Liberacion del resultado de la consulta
        $stmtInsertProfessorDepartment->close();
        while ($this->connection->more_results() && $this->connection->next_result()) {
            $extraResult = $this->connection->store_result();
            if ($extraResult) {
                $extraResult->free();
            }
        }

        //INSERCION RELACION Usuario_Docente-Rol
        $querySelectRoleProfessor = "SELECT id_role FROM `Roles` WHERE role = 'Professor';";
        $resultSelectRoleProfessor = $this->connection->execute_query($querySelectRoleProfessor);
        while ($row = $resultSelectRoleProfessor->fetch_array(MYSQLI_ASSOC)) {
            $idRoleProfessor = intval($row['id_role']);
        }

        $resultSelectRoleProfessor->free();
        while ($this->connection->more_results() && $this->connection->next_result()) {
            $extraResult = $this->connection->store_result();
            if ($extraResult) {
                $extraResult->free();
            }
        }

        $queryInsertUsuerRol = "INSERT INTO `RolesUsersProfessor` (id_role_professor, id_user_professor, status_role_professor) VALUES (?, ?, TRUE);";
        $stmtInsertUserRole = $this->connection->prepare($queryInsertUsuerRol);
        $stmtInsertUserRole->bind_param('ii', $idRoleProfessor, $idUserProfessor['idUserProfessor']);
        
        if (!($stmtInsertUserRole->execute())) {
            $this->connection->rollback();
            $stmtInsertUserRole->close();
            $errors[] = 'Insercion Usuario_docente-Rol fallida.';
        }

        return $response = [
            'success' => true,
            'message' => 'Profesor creado.',
            'errors' => $errors,
            'userPassword' => $passwordUser,
            'usernameProfessor' => $idProfessor
        ];
    }
    
    /**
     * Metodo auxiliar para la insercion de un usuario docente.
     * @see createProfessor
     * 
     * @param int $idProfessor ID del profesor a quien pertenece el usuario.
     * @param string $password Contrasena del usuario.
     * 
     * @return array Arreglo asociativo con el resultado de la insercion (success) y mensaje de retroalimentacion (message).
     *      FALLO EN LA INSERCION DEL USUARIO: 'success'=>false
     *      SIN FALLOS EN LA INSERCION: 'success'=>true (Se retorna el username del usuario)
     * 
     * @author @AngelNolasco
     * @created 01/12/2024
     */
    public function insertUserProfessor(int $idProfessor, string $password) {
        $hashPassword = Encryption::hashPassword($password);
    
        // Inserción del usuario profesor
        $queryInsertUserProfessor = "INSERT INTO `UsersProfessors` (username_user_professor, password_user_professor, status_user_professor) VALUES (?, ?, TRUE);";
        $stmtInsertUserProfessor = $this->connection->prepare($queryInsertUserProfessor);
        $stmtInsertUserProfessor->bind_param('is', $idProfessor, $hashPassword);
    
        if (!$stmtInsertUserProfessor->execute()) { // Fallo en la inserción
            return [
                'success' => false,
                'message' => 'Fallo en la inserción del usuario: ' . $stmtInsertUserProfessor->error
            ];
        }
    
        // Obtiene el último ID insertado
        $idUserProfessor = $this->connection->insert_id;
    
        // Cierre del statement
        $stmtInsertUserProfessor->close();
    
        // Verifica si el ID fue correctamente asignado
        if (!$idUserProfessor) {
            return [
                'success' => false,
                'message' => 'No se pudo obtener el ID del usuario insertado'
            ];
        }
    
        return [
            'success' => true,
            'message' => 'Usuario creado exitosamente.',
            'idUserProfessor' => $idUserProfessor
        ];
    }

    /**
     * Metodo para actualizar la foto, el las obligaciones y el centro regional de un docente.
     * 
     * @param int $idProfessor El numero de cuenta del docente.
     * @param string|null $pictureProfessor La foto del docente en formato BLOB
     * @param int|null $idProfessorObligation El ID que hace referencias a las obligaciones definidas a nivel institucional.
     * @param int|null $idRegionalCenter El ID del centro regional al que esta vinculado el docente.
     * 
     * @author @AngelNolasco
     * @created 09/12/2024
     */
    public function updateProfessor (int $idProfessor, string|null $pictureProfessor=null, int|null $idProfessorObligation=null, int|null $idRegionalCenter=null) {
        if (!(isset($idProfessor))) {
            return $response = [
                'status' => 'warning',
                'message' => 'Numero empleado docente no definido o nulo.'
            ];
        }

        $querySelectProfessor = "SELECT * FROM Professors WHERE id_professor = ?;";
        $stmtSelectProfessor = $this->connection->prepare($querySelectProfessor);
        $stmtSelectProfessor->bind_param('i', $idProfessor);
        $resultSelectProfessor = $stmtSelectProfessor->get_result();

        if($resultSelectProfessor->num_rows > 0) {
            if(isset($pictureProfessor) && isset($idProfessorObligation) && isset($idRegionalCenter)) {
                $queryUpdateProfessor = "UPDATE `Professors` SET id_professors_obligations = ?, id_regional_center = ?, picture_professor = ? WHERE id_professor = ?;";
                $params = [$pictureProfessor, $idProfessorObligation, $idRegionalCenter];
            } elseif (isset($pictureProfessor) && isset($idProfessorObligation)) {
                $queryUpdateProfessor = "UPDATE `Professors` SET id_professors_obligations = ?, picture_professor = ? WHERE id_professor = ?;";
                $params = [$pictureProfessor, $idProfessorObligation];
            } elseif (isset($pictureProfessor) && isset($idRegionalCenter)) {
                $queryUpdateProfessor = "UPDATE `Professors` SET id_regional_center = ?, picture_professor = ? WHERE id_professor = ?;";
                $params = [$pictureProfessor, $idRegionalCenter];
            } elseif (isset($idProfessorObligation) && isset($idRegionalCenter)) {
                $queryUpdateProfessor = "UPDATE `Professors` SET id_professors_obligations = ?, id_regional_center = ? WHERE id_professor = ?;";
                $params = [$idProfessorObligation, $idRegionalCenter];
            } else {
                return $response = [
                    'status' => 'error',
                    'message' => 'Parametros nulos o no definidos. No hubo actualizacion.'
                ];
            }

            $resultUpdateProfessor = $this->connection->execute_query($queryUpdateProfessor, $params);
            if ($resultUpdateProfessor) {
                return $response = [
                    'status' => 'success',
                    'message' => 'Actualizacion realizada satisfactoriamente.'
                ];
            } else {
                return $response = [
                    'status' => 'error',
                    'message' => 'Error en la ejecucion de la consulta.'
                ];
            }
        } else {
            return $response = [
                'status' => 'info',
                'message' => 'Docente no encontrado.'
            ];
        }
    }

    /**
     * Metodo para actualizar el estado de un docente.
     * 
     * @param int $idProfessor El numero de cuenta del docente.
     * 
     * @return array $response Arreglo asociativo con el resultado del metodo (success), un mensaje de retroalimentacion (message) y, en caso de exito, el nuevo status del docente (newStatus).
     * 
     * @author @AngelNolasco
     * @created 08/12/2024
     */
    public function changeStatusProfessor (int $idProfessor) {
        $querySelectProfessor = "SELECT status_professor FROM `Professors` WHERE id_professor = ?;";
        $resultSelectProfessor = $this->connection->execute_query($querySelectProfessor, [$idProfessor]);
        if (!($resultSelectProfessor)) {
            return $response = [
                'success' => false,
                'message' => 'Docente no encontrado.'
            ];
        }
        while ($row = $resultSelectProfessor->fetch_assoc()) {
            $currentStatus = $row['status_professor'];
        }

        if ($currentStatus == 1) { //El status actual es TRUE
            $queryUpdateStatus = "UPDATE `Professors` SET status_professor = 0 WHERE id_professor = ?;";
            if ($resultUpdateStatus = $this->connection->execute_query($queryUpdateStatus, [$idProfessor])) {
                return $response = [
                    'success' => true,
                    'message' => 'Actualizacion de estado realizada correctamente.',
                    'newStatus' => false
                ];
            } else {
                return $response = [
                    'success' => false,
                    'mesage' => 'No se pudo actualizar el estado.'
                ];
            }
        } else { //El status actual es FALSE
            $queryUpdateStatus = "UPDATE `Professors` SET status_professor = 1 WHERE id_professor = ?;";
            if ($resultUpdateStatus = $this->connection->execute_query($queryUpdateStatus, [$idProfessor])) {
                return $response = [
                    'success' => true,
                    'message' => 'Actualizacion de estado realizada correctamente.',
                    'newStatus' => true
                ];
            } else {
                return $response = [
                    'success' => false,
                    'mesage' => 'No se pudo actualizar el estado.'
                ];
            }
        }
    }

    /**
     * Metodo para obtener todos los docentes de una facultad.
     * 
     * @param int $idFaculty Numero identificador de la facultad de la que se quiere obtener los docentes.
     * 
     * @return array $response Arreglo asociativo con el resultado del metodo (success), un mensaje de retroalimentacion (message) y, en caso de exito, un arreglo con los docentes de la facultad (professors).
     * 
     * @author @AngelNolasco
     * @created 08/12/2024
     */
    public function getProfessorsByFaculty (int $idFaculty) {
        if (!(isset($idFaculty))) {
            return $response = [
                'success' => false,
                'message' => 'Codigo de facultad no definido o nulo.'
            ];
        }

        $querySelectProfessorsByFaculty = "CALL SP_GET_PROFESSORS_BY_FACULTY(?)";
        $resultSelectProfessorByFaculty = $this->connection->execute_query($querySelectProfessorsByFaculty, [$idFaculty]);

        if (!($resultSelectProfessorByFaculty)) {
            return $response = [
                'success' => false,
                'message' => 'Error en la ejecucion de la consulta.'
            ];
        }

        $professors = [];
        while ($row = $resultSelectProfessorByFaculty->fetch_assoc()) {
            $professors [] = $row;
        }

        if (empty($professors)) {
            return $response = [
                'success' => true,
                'message' => 'No se encontraron docentes asignados a la facultad.'
            ];
        } else {
            return $response = [
                'success' => true,
                'message' => 'Ejecucion de consulta exitosa. Campos: id_professor, names_professor, lastnames_professors, email_professor, name_regional_center, name_departmet, status_professor.',
                'professors' => $professors
            ];
        }
    }


}
?>