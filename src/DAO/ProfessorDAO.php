<?php
include_once 'util/jwt.php';
require_once 'util/encryption.php';
/**
 * Objeto de acceso a datos y controlador de maestros.
 * 
 * @property string $host Direccion de host de base de datos
 * @property string $user Usuario de acceso a la base de datos
 * @property string $password Clave de acceso del respectivo usuario
 * @property string $dbName Nombre de la base de datos
 * @property mysqli $connection Objeto de conexion con la base de datos
 */

class ProfessorsDAO {
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
     * Metodo para la autenticacion de usuarios docentes.
     * 
     * @param string $username Username de usuario docente.
     * @param string $password Contrasena de usuario docente.
     * 
     * @return array $response Arreglo asociativo que indica si la autenticacion fallo o no junto con un mensaje de retroalimentacion y el valor del token (nulo en caso de fallo de autenticacion).
     * 
     * @author @AngelNolasco
     * @created 02/12/2024
     */
    public function authProfessor(int $username, string $password) {
        if (isset($username) && isset($password)) {
            //Busqueda del usuario en la base de datos
            $query = "SELECT id_user_professor, password_user_professor FROM `UsersProfessors` WHERE username_user_professor = ?;";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('i', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) { //Se encontro un usuario con ese username
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $auxID = intval($row['id_user_professor']);
                $hashPassword = $row['password_user_professor'];
                $coincidence = Encryption::verifyPassword($password, $hashPassword);

                if ($coincidence) { //Si la contrasena ingresada coincide con la de la BD
                    //Consulta para obtener los accesos del usuario
                    $queryAccessArray = "SELECT `AccessControl`.id_access_control, `Roles`.id_role, `ProfessorsDepartments`.id_department FROM `AccessControl`
                    INNER JOIN `AccessControlRoles` ON `AccessControl`.id_access_control = `AccessControlRoles`.id_access_control
                    INNER JOIN `Roles` ON `AccessControlRoles`.id_role = `Roles`.id_role
                    INNER JOIN `RolesUsersProfessor` ON `Roles`.id_role = `RolesUsersProfessor`.id_role_professor
                    INNER JOIN `UsersProfessors` ON `RolesUsersProfessor`.id_user_professor = `UsersProfessors`.id_user_professor
                    INNER JOIN `Professors` ON `UsersProfessors`.username_user_professor = `Professors`.id_professor
                    INNER JOIN `ProfessorsDepartments` ON `Professors`.id_professor = `ProfessorsDepartments`.id_professor
                    WHERE `UsersProfessors`.id_user_professor = ?;";
                    $stmtAccessArray = $this->connection->prepare($queryAccessArray);
                    $stmtAccessArray->bind_param('i', $auxID);
                    $stmtAccessArray->execute();
                    $resultAccessArray = $stmtAccessArray->get_result();

                    $accessArray = [];
                    $idRole = 0;
                    $idDepartment = 0;
                    while ($rowAccess = $resultAccessArray->fetch_array(MYSQLI_ASSOC)) {
                        $accessArray[] = $rowAccess['id_access_control'];
                        $idRole = $rowAccess['id_role'];
                        $idDepartment = $rowAccess['id_department'];
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
                        'idRole' => $idRole,
                        'idDepartment' => $idDepartment
                    ];
                    $newToken = JWT::generateToken($payload); //Generacion del token a partir del payload
                    
                    //Consulta de busqueda del usuario en la tabla relacional de tokens respectiva
                    $queryCheck = "SELECT id_user_professor FROM TokenUserProfessor WHERE id_user_professor = ?;";
                    $stmtCheck = $this->connection->prepare($queryCheck);
                    $stmtCheck->bind_param('i', $auxID);
                    $stmtCheck->execute();
                    $resultCheck = $stmtCheck->get_result();

                    if ($resultCheck->num_rows > 0) { //El usuario existe, se actualiza el token
                        $queryUpdate = "UPDATE `TokenUserProfessor` SET token_professor = ? WHERE id_user_professor = ?;";
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
                        $queryInsert = "INSERT INTO `TokenUserProfessor` (id_user_professor, token_professor) VALUES (?, ?);";
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
                        'typeUser' => 14
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
     * Metodo para obtener las solicitudes de cancelacion excepcional de clases, las solicitudes de cambio de centro regional y las solicitudes de cambio de carrera que aun no tienen respuesta.
     * 
     * @param int $idProfessor El numero de cuenta del docente que las solicita (para verificar que sea un coordinador academico).
     * 
     * @return array $response Arreglo asociativo con el resultado del metodo (success), un mensaje de retroalimentacion (message), un arreglo con los posibles errores que se pudieron haber dado (errors) y, en caso de exito, las solicitudes sin respuesta antes mencionadas.
     * 
     * @author @AngelNolasco
     * @created 08/12/2024
     */
    public function getRequests (int $idProfessor) {
        $querySearchAcademicCoordinator = "SELECT `Roles`.role FROM `UsersProfessors`
        INNER JOIN `RolesUsersProfessor` ON `UsersProfessors`.id_user_professor = `RolesUsersProfessor`.id_user_professor
        INNER JOIN `Roles` ON `RolesUsersProfessor`.id_role_professor = `Roles`.id_role
        WHERE `UsersProfessors`.username_user_professor = ?;";
        $stmtSearchAcademicCoordinator = $this->connection->prepare($querySearchAcademicCoordinator);
        $stmtSearchAcademicCoordinator->bind_param('i', $idProfessor);
        $stmtSearchAcademicCoordinator->execute();
        $resultSearchAcademicCoordinator = $stmtSearchAcademicCoordinator->get_result();
        $roles = [];
        while ($row = $resultSearchAcademicCoordinator->fetch_array()) {
            $roles [] = $row;
        }

        if (in_array('Coordinator', $roles)) {
            $requestsExceptionalCancellationClasses = [];
            $requestsChangeRegionalCenter = [];
            $requestsChangeUndergraduate = [];
            $errors = [];

            $querySelectRequestsExceptionalCancellationClassesWithOutResolution = "SELECT requests.*
            FROM `RequestsCancellationExceptionalClasses` requests
            LEFT JOIN `ResolutionRequestsCancellationExceptionalClasses` resolutions
            ON requests.id_requests_cancellation_exceptional_classes = resolutions.id_requests_cancellation_exceptional_classes
            WHERE resolutions.id_requests_cancellation_exceptional_classes IS NULL;";
            
            $querySelectRequestsRegionalCentersChangeWithOutResolution = "SELECT requests.* FROM `RegionalCentersChangeRequestsStudents` requests
            LEFT JOIN `ResolutionRegionalCentersChangeRequestsStudents` resolutions
            ON requests.id_regional_center_change_request_student = resolutions.id_regional_center_change_request_student
            WHERE resolutions.id_regional_center_change_request_student IS NULL;";

            $querySelectRequestsUndergraduatesChangeWithOutResolution = "SELECT requests.* FROM `UndergraduateChangeRequestsStudents` requests
            LEFT JOIN `ResolutionUndergraduateChangeRequestsStudents` resolutions
            ON requests.id_undergraduate_change_request_student = resolutions.id_undergraduate_change_request_student
            WHERE resolutions.id_undergraduate_change_request_student IS NULL;";

            if ($resultRequestsCancellation = $this->connection->execute_query($querySelectRequestsExceptionalCancellationClassesWithOutResolution)) {
                while ($row = $resultRequestsCancellation->fetch_assoc()) {
                    $requestsExceptionalCancellationClasses [] = $row;
                }
            } else {
                $errors [] = "No se pudieron obtener las solicitudes de cancelacion excepcional de clase.";
            }

            if ($resultRequestsChangeReionalCenter = $this->connection->execute_query($querySelectRequestsRegionalCentersChangeWithOutResolution)) {
                while ($row = $resultRequestsChangeReionalCenter->fetch_assoc()) {
                    $requestsChangeRegionalCenter [] = $row;
                }
            } else {
                $errors [] = "No se pudieron obtener las solicitudes de cambio de centro.";
            }

            if ($resultRequestsChangeUndergraduate = $this->connection->execute_query($querySelectRequestsUndergraduatesChangeWithOutResolution)) {
                while ($row = $resultRequestsChangeUndergraduate->fetch_assoc()) {
                    $requestsChangeUndergraduate [] = $row;
                }
            } else {
                $errors [] = "No se pudieron obtener las solicitudes de cambio de carrera.";
            }

            return $response = [
                'success' => true,
                'message' => 'Consultas de solicitudes sin repuesta finalizadas.',
                'requestsExceptionalCancellation' => $requestsExceptionalCancellationClasses,
                'requestsChangeRegionalCenter' => $requestsChangeRegionalCenter,
                'requestsChangeUndergraduate' => $requestsChangeUndergraduate,
                'errors' => $errors
            ];

        } else { //El docente no es coordinador academico
            return $response = [
                'success' => false,
                'message' => 'Docente no identificado como coordinador academico.'
            ];
        }
    }

    /**
     * Metodo para obtener las clases asignadas a un docente especifico.
     * 
     * @param int $idProfessor El numero de cuenta del docente del que se quiere obtener sus clases asignadas.
     * 
     * @return array $response Arreglo asociativo con el resultado de la consulta (success), un mensaje de retroalimentacion (message) y, en caso de exito, un arreglo con todas sus clases asignadas (assignedClasses).
     * 
     * @author @AngelNolasco
     * @created 08/12/2024
     */
    public function getAssignedClasses (int $idProfessor) {
        $querySelectAssignedClasses = "SELECT classes.id_class as class_code, classes.name_class, `ClassSections`.id_class_section as section_code,
        `ClassSectionsDays`.id_day as section_day, `AcademicSchedules`.start_timeof_classes as hi,
        `AcademicSchedules`.end_timeof_classes as hf, `ClassSectionsProfessor`.class_presentation_video as url_video
        FROM `ClassSections`
        INNER JOIN classes ON `ClassSections`.id_class = classes.id_class
        INNER JOIN `ClassSectionsDays` ON `ClassSections`.id_class_section = `ClassSectionsDays`.id_class_section
        INNER JOIN `AcademicSchedules` ON `ClassSections`.id_academic_schedules = `AcademicSchedules`.id_academic_schedules
        INNER JOIN `ClassSectionsProfessor` ON `ClassSections`.id_class_section = `ClassSectionsProfessor`.id_class_section
        INNER JOIN `Professors` ON `ClassSections`.id_professor_class_section = `Professors`.id_professor
        WHERE `Professors`.id_professor = ?;";
        $stmtAssignedClasses = $this->connection->prepare($querySelectAssignedClasses);
        $stmtAssignedClasses->bind_param('i', $idProfessor);
        $stmtAssignedClasses->execute();
        $resultAssignedClasses = $stmtAssignedClasses->get_result();

        if ($resultAssignedClasses->num_rows > 0) {
            $groupedClasses = [];

            while ($row = $resultAssignedClasses->fetch_array(MYSQLI_ASSOC)) {
                $sectionCode = $row['section_code'];

                if (!isset($groupedClasses[$sectionCode])) {
                    $groupedClasses[$sectionCode] = [
                        'class_code' => $row['class_code'],
                        'name_class' => $row['name_class'],
                        'section_code' => $row['section_code'],
                        'section_days' => [],
                        'hi' => $row['hi'],
                        'hf' => $row['hf'],
                        'url_video' => $row['url_video']
                    ];
                }

                $groupedClasses[$sectionCode]['section_days'][] = $row['section_day'];
            }

            foreach ($groupedClasses as &$class) {
                $class['section_days'] = implode(', ', $class['section_days']);
            }

            return $response = [
                'success' => true,
                'message' => 'Consulta de clases exitosa.',
                'assignedClasses' => array_values($groupedClasses)
            ];
        } else {
            return $response = [
                'success' => true,
                'message' => 'El docente no tiene clases asignadas.'
            ];
        }
    }

    /**
     * 
     */
    public function getReportsByPeriod (int $idProfessor) {
        
    }
}
?>