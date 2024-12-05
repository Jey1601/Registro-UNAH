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

                    //Creacion del payload con el username y el arreglo de accesos del usuario administrador de admisiones
                    $payload = [
                        'userAdmissionAdmin' => $username,
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
                        'typeUser' => 'facultyAdmin'
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
     * Metodo auxiliar para la insercion de un usuario docente. Usado en el metodo createProfessor.
     * 
     * @param int $idProfessor ID del profesor a quien pertenece el usuario.
     * @param string $password Contrasena del usuario.
     * 
     * @return array Arreglo asociativo con el resultado de la insercion (success) y mensaje de retroalimentacion (message).
     *      FALLO EN LA INSERCION DEL USUARIO: 'success'=>false
     *      SIN FALLOS EN LA INSERCION: 'success'=>true (Se retorna el username del usuario)
     */
    public function insertUserProfessor(int $idProfessor, string $password) {
        $hashPassword = Encryption::hashPassword($password);

        $queryInsertUserProfessor = "INSERT INTO `UsersProfessors` (username_user_professor, password_user_professor, status_user_professor) VALUES (?, ?, TRUE);";
        $stmtInsertUserProfessor = $this->connection->prepare($queryInsertUserProfessor);
        $stmtInsertUserProfessor->bind_param('is', $idProfessor, $hashPassword);

        if(!($stmtInsertUserProfessor->execute())) { //Fallo en la insercion del usuario
            return [
                'success' => false,
                'message' => 'Fallo en la insercion del usuario'
            ];
        }

        $queryIdUserProfessor = "SELECT id_user_professor FROM `UsersProfessors` WHERE username_user_professor = ?;";
        $stmtIdUser = $this->connection->prepare($queryIdUserProfessor);
        $stmtIdUser->bind_param('i', $idProfessor);
        $resultIdUser = $stmtIdUser->get_result();

        while ($row = $resultIdUser->fetch_array(MYSQLI_ASSOC)) {
            $idUserProfessor = intval($row['id_user_professor']);
        }

        //Liberacion del resultado de la consulta
        $resultIdUser->free();
        $stmtIdUser->close();
        while ($this->connection->more_results() && $this->connection->next_result()) {
            $extraResult = $this->connection->store_result();
            if ($extraResult) {
                $extraResult->free();
            }
        }

        return [
            'success' => true,
            'message' => 'Usuario creado exitosamente.',
            'idUserProfessor' => $idUserProfessor
        ];
    }

    /**
     * Metodo para crear la relacion entre un profesor y sus distintos roles.
     * 
     * @param array $roles Arreglo con los roles del docente.
     * @param int $idUserProfessor ID del usuario del docente.
     * 
     * @return array $response Arreglo asociativo con el resultado de la insercion de la relacion rol-usuario_docente (success) y mensaje de retroalimentacion (message).
     *      FALLO EN LA BUSQUEDA DE ROLES: 'success'=>false
     *      FALLO EN LA INSERCION DE LA RELACION ROL-USUARIO_DOCENTE: 'success'=>false
     *      NO SE PRESENTAN FALLOS: 'success'=>true
     */
    public function rolesUserProfessor (array $roles, int $idUserProfessor) {
        $querySelectIdRole = "SELECT id_role FROM Roles WHERE role = ?";
        $stmtSelectIdRole = $this->connection->prepare($querySelectIdRole);
        $stmtSelectIdRole->bind_param('s', $roleUserProfessor); //RECORRER ARREGLO DE ROLES (METODO POR REFINAR)
        $stmtSelectIdRole->execute();
        $resultSelectIdRole = $stmtSelectIdRole->get_result();

        if(!($resultSelectIdRole->num_rows > 0)) {
            $this->connection->rollback();
            $stmtSelectIdRole->close();

            return $response = [
                'success' => false,
                'message' => 'Rol no encontrado'
            ];
        }
        
        $rowSelectIdRole = $resultSelectIdRole->fetch_array(MYSQLI_ASSOC);
        $idUserRole = $rowSelectIdRole['id_role'];
        
        $queryInsertRoleUserProfessor = "INSERT INTO `RolesUsersProfessor` (id_role_professor, id_user_professor, status_role_professor) VALUES (?, ?, TRUE);";
        $stmtInsertRoleUserProfessor = $this->connection->prepare($queryInsertRoleUserProfessor);
        
        if(!($stmtInsertRoleUserProfessor->execute())) {
            $this->connection->rollback();
            $stmtInsertRoleUserProfessor->close();

            return $response = [
                'success' => false,
                'message' => 'Fallo en insercion de Rol-Usuario.'
            ];
        }

        //Liberacion del resultado de la consulta
        $stmtInsertRoleUserProfessor->close();
        while ($this->connection->more_results() && $this->connection->next_result()) {
            $extraResult = $this->connection->store_result();
            if ($extraResult) {
                $extraResult->free();
            }
        }

        return $response = [
            'success' => true,
            'message' => 'Insercion de Rol-Usuario exitosa.'
        ];
    }

    /**
     * Metodo auxiliar par obtener el ID de un centro regional por su nombre. Usado en metodo createProfessor.
     * 
     * @param string $nameRegionalCenter Nombre del centro regional.
     * @return bool|int False en caso de fallo en la busqueda, el ID del centro regional en caso de exito.
     */
    public function getIdRegionalCenterByName (string $nameRegionalCenter) {
        $querySearchRegionalCenter = "SELECT id_regional_center FROM `RegionalCenters` WHERE name_regional_center = ?";
        $stmtSearchRegionalCenter = $this->connection->prepare($querySearchRegionalCenter);
        $stmtSearchRegionalCenter->bind_param('s', $nameRegionalCenter);
        $stmtSearchRegionalCenter->execute();
        $resultSearchRegionalCenter = $stmtSearchRegionalCenter->get_result();

        if(!($resultSearchRegionalCenter->num_rows > 0)) { //Centro regional no encontrado
            return false;
        }

        $rowSearchRegionalCenter = $resultSearchRegionalCenter->fetch_array(MYSQLI_ASSOC);
        $idRegionalCenter = $rowSearchRegionalCenter['id_regional_center'];

        //Liberacion del resultado de la consulta
        $resultSearchRegionalCenter->free();
        $stmtSearchRegionalCenter->close();
        while ($this->connection->more_results() && $this->connection->next_result()) {
            $extraResult = $this->connection->store_result();
            if ($extraResult) {
                $extraResult->free();
            }
        }

        return $idRegionalCenter;
    }

    /**
     * Metodo auxiliar para obtener el ID de un departamento por su nombre. Usado en metodo createProfessor.
     * 
     * @param string $departmentName Nombre del departamento.
     * 
     * @return array Arreglo asociativo con el resultado de la consulta (success) y el ID del departamento (nulo en caso de fallo).
     */
    public function getIdDeparmentByName (string $departmentName) {
        $querySelectDepartment = "SELECT id_department FROM Departments WHERE name_departmet = ?";
        $stmtSelectDepartment = $this->connection->prepare($querySelectDepartment);
        $stmtSelectDepartment->bind_param('s', $departmentName);
        $resultSelectDepartment = $stmtSelectDepartment->get_result();

        if ($resultSelectDepartment->num_rows > 0) {
            $rowSelectDeparment = $resultSelectDepartment->fetch_assoc();
            $idDepartment = $rowSelectDeparment['id_department'];

            //Liberacion del resultado de la consulta
            $resultSelectDepartment->free();
            $stmtSelectDepartment->close();
            while ($this->connection->more_results() && $this->connection->next_result()) {
                $extraResult = $this->connection->store_result();
                if ($extraResult) {
                    $extraResult->free();
                }
            }

            return [
                'success' => true,
                'idDepartment' => $idDepartment
            ];
        } else { //Departamento no encontrado
            //Liberacion del resultado de la consulta
            $stmtSelectDepartment->close();
            while ($this->connection->more_results() && $this->connection->next_result()) {
                $extraResult = $this->connection->store_result();
                if ($extraResult) {
                    $extraResult->free();
                }
            }

            return [
                'success' => false,
                'idDeparment' => null
            ];
        }

    }

}
?>