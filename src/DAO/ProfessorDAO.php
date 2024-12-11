<?php
include_once 'util/jwt.php';
require_once 'util/encryption.php';
require_once 'util/FPDF/fpdf.php';
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
                        'username' => $username,
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
    public function getPendingRequestsCancellationExceptionalClass (int $idProfessor) {
        $querySearchAcademicCoordinator = "SELECT `Roles`.role FROM `UsersProfessors`
        INNER JOIN `RolesUsersProfessor` ON `UsersProfessors`.id_user_professor = `RolesUsersProfessor`.id_user_professor
        INNER JOIN `Roles` ON `RolesUsersProfessor`.id_role_professor = `Roles`.id_role
        WHERE `UsersProfessors`.username_user_professor = ? AND `UsersProfessors`.status_user_professor = 1;";
        $stmtSearchAcademicCoordinator = $this->connection->prepare($querySearchAcademicCoordinator);
        $stmtSearchAcademicCoordinator->bind_param('i', $idProfessor);
        $stmtSearchAcademicCoordinator->execute();
        $resultSearchAcademicCoordinator = $stmtSearchAcademicCoordinator->get_result();
        $roles = [];
        while ($row = $resultSearchAcademicCoordinator->fetch_assoc()) {
            $roles [] = $row['role'];
        }

        if (in_array('Coordinator', $roles)) {
            $requestsExceptionalCancellationClasses = [];
            $errors = [];

            $queryGetPendingRequests = "CALL SP_GET_PENDING_REQUESTS_CANCELLATION_EXCEPTIONAL_BY_COORDINATOR(?);";
            if ($resultRequestsCancellation = $this->connection->execute_query($queryGetPendingRequests, [$idProfessor])) {
                while ($row = $resultRequestsCancellation->fetch_assoc()) {
                    $requestsExceptionalCancellationClasses [] = $row;
                }
            } else {
                $errors [] = "No se pudieron obtener las solicitudes de cancelacion excepcional de clase.";
            }

            return $response = [
                'success' => true,
                'message' => 'Consultas de solicitudes sin repuesta finalizadas.',
                'requestsExceptionalCancellation' => $requestsExceptionalCancellationClasses,
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
     * @author @AngelNolasco
     * @created 10/12/2024
     */
    public function getDetailsRequestCancellationExceptional (int $idRequest) {
        if (!isset($idRequest)) {
            return $response = [
                'status' => 'error',
                'message' => 'Codigo de solicitud no definido o nulo.'
            ];
        }

        $queryGetDetails = "CALL SP_GET_DETAILS_REQUEST_BY_ID(?)";
        if ($resultGetDetails = $this->connection->execute_query($queryGetDetails, [$idRequest])) {
            $groupedSections = [];
            while ($row = $resultGetDetails->fetch_array(MYSQLI_ASSOC)) {
                $codeRequest = $row['id_request'];

                if (!isset($groupedSections['$codeRequest'])) {
                    $groupedSections['$codeRequest'] = [
                        'idStudent' => $row['id_student'],
                        'nameStudent' => $row['name_student'],
                        'lastnameStudent' => $row['lastname_student'],
                        'emailStudent' => $row['email_student'],
                        'regionalCenter' => $row['name_regional_center'],
                        'reason' => $row['reason'],
                        'document' => $row['document_justification'],
                        'evidence' => $row['evidence']
                    ];
                }

                $groupedSections['$codeRequest']['idSectionClass'][] = [
                    'idSection' => $row['id_class_section'],
                    'nameClass' => $row['name_class']
                ];
            }

            return $response = [
                'status' => 'success',
                'message' => 'Se obtuvo satisfactoriamente los detalles de la solicitud.',
                'detailsRequest' => array_values($groupedSections)
            ];
        } else {
            return $response = [
                'status' => 'error',
                'message' => 'No se pudieron obtener los detalles de la solicitud.'
            ];
        }
    }

    /**
     * @author @AngelNolasco
     * @created 09/12/2024
     */
    public function respondRequestCancellationExceptional (int $idProfessor, int $idRequest) {
        $querySearchAcademicCoordinator = "SELECT `Roles`.role FROM `UsersProfessors`
        INNER JOIN `RolesUsersProfessor` ON `UsersProfessors`.id_user_professor = `RolesUsersProfessor`.id_user_professor
        INNER JOIN `Roles` ON `RolesUsersProfessor`.id_role_professor = `Roles`.id_role
        WHERE `UsersProfessors`.username_user_professor = ? AND `UsersProfessors`.status_user_professor = 1;";
        $stmtSearchAcademicCoordinator = $this->connection->prepare($querySearchAcademicCoordinator);
        $stmtSearchAcademicCoordinator->bind_param('i', $idProfessor);
        $stmtSearchAcademicCoordinator->execute();
        $resultSearchAcademicCoordinator = $stmtSearchAcademicCoordinator->get_result();
        $roles = [];
        while ($row = $resultSearchAcademicCoordinator->fetch_array(MYSQLI_ASSOC)) {
            $roles [] = $row['role'];
        }

        if (!in_array('Coordinator', $roles)) {
            return $response = [
                'status' => 'warning',
                'message' => 'El docente no tiene el rol de coordinador academico.'
            ];
        }

        $queryGetIdAcademicCoordinator = "SELECT `AcademicCoordinator`.id_academic_coordinator FROM `AcademicCoordinator`
        INNER JOIN `Professors` ON `AcademicCoordinator`.id_professor = `Professors`.id_professor
        WHERE `Professors`.id_professor = ? AND `AcademicCoordinator`.status_academic_coordinator = TRUE;";
        $resultGetIdAcademicCoordinator = $this->connection->execute_query($queryGetIdAcademicCoordinator, [$idProfessor]);
        $idAcademicCoordinator = 0;
        while ($row = $resultGetIdAcademicCoordinator->fetch_assoc()) {
            $idAcademicCoordinator = $row['id_academic_coordinator'] ?? 0;
            break;
        }

        if($idAcademicCoordinator == 0) {
            return $response = [
                'status' => 'warning',
                'message' => 'El docente no esta registrado como coordinador academico.'
            ];
        }

        $queryInsertRespondRequest = "INSERT INTO `ResolutionRequestsCancellationExceptionalClasses` (id_academic_coordinator, id_requests_cancellation_exceptional_classes, resolution_request_student, date_resolution)
        VALUES (?, ?, TRUE, CURRENT_DATE());";
        $resultInsertRespondRequest = $this->connection->execute_query($queryInsertRespondRequest, [$idAcademicCoordinator, $idRequest]);

        if($resultInsertRespondRequest) {
            return $response = [
                'status' => 'success',
                'message' => 'La solicitud con codigo '.$idRequest.' ha sido registrada como contestada.'
            ];
        } else {
            $this->connection->rollback();
            return $response = [
                'status' => 'error',
                'message' => 'No se ha registrado el dictamen de la solicitud.'
            ];
        }
    }

    /**
     * @author @AngelNolasco
     * @created 10/12/2024
     */
    public function respondeRequestListSectionClass (int $idProfessor, int $idRequest, string $idStudent, $arrayClassSectionIdResolution) {
        $querySearchAcademicCoordinator = "SELECT `Roles`.role FROM `UsersProfessors`
        INNER JOIN `RolesUsersProfessor` ON `UsersProfessors`.id_user_professor = `RolesUsersProfessor`.id_user_professor
        INNER JOIN `Roles` ON `RolesUsersProfessor`.id_role_professor = `Roles`.id_role
        WHERE `UsersProfessors`.username_user_professor = ? AND `UsersProfessors`.status_user_professor = 1;";
        $stmtSearchAcademicCoordinator = $this->connection->prepare($querySearchAcademicCoordinator);
        $stmtSearchAcademicCoordinator->bind_param('i', $idProfessor);
        $stmtSearchAcademicCoordinator->execute();
        $resultSearchAcademicCoordinator = $stmtSearchAcademicCoordinator->get_result();
        $roles = [];
        while ($row = $resultSearchAcademicCoordinator->fetch_array(MYSQLI_ASSOC)) {
            $roles [] = $row['role'];
        }

        if (!in_array('Coordinator', $roles)) {
            return $response = [
                'status' => 'warning',
                'message' => 'El docente no tiene el rol de coordinador academico.'
            ];
        }

        $queryGetIdAcademicCoordinator = "SELECT `AcademicCoordinator`.id_academic_coordinator FROM `AcademicCoordinator`
        INNER JOIN `Professors` ON `AcademicCoordinator`.id_professor = `Professors`.id_professor
        WHERE `Professors`.id_professor = ? AND `AcademicCoordinator`.status_academic_coordinator = TRUE;";
        $resultGetIdAcademicCoordinator = $this->connection->execute_query($queryGetIdAcademicCoordinator, [$idProfessor]);
        $idAcademicCoordinator = 0;
        while ($row = $resultGetIdAcademicCoordinator->fetch_assoc()) {
            $idAcademicCoordinator = $row['id_academic_coordinator'] ?? 0;
            break;
        }

        if($idAcademicCoordinator == 0) {
            return $response = [
                'status' => 'warning',
                'message' => 'El docente no esta registrado como coordinador academico.'
            ];
        }

        $querySelectIdListsIdSections = "SELECT `ListClassSectionCancellationExceptional`.id_list_class_section_cancellation_exceptional, `ListClassSectionCancellationExceptional`.id_class_section FROM `ListClassSectionCancellationExceptional`
        INNER JOIN `RequestsCancellationExceptionalClasses` ON `ListClassSectionCancellationExceptional`.id_requests_cancellation_exceptional_classes = `RequestsCancellationExceptionalClasses`.id_requests_cancellation_exceptional_classes
        WHERE `RequestsCancellationExceptionalClasses`.id_requests_cancellation_exceptional_classes = ?;";

        $resultSelectIdListsIdSections = $this->connection->execute_query($querySelectIdListsIdSections, [$idRequest]);
        if (!$resultSelectIdListsIdSections) {
            return $reponse = [
                'status' => 'error',
                'message' => 'No se ha podido obtener la lista de las secciones solicitadas para cancelacion.'
            ];
        }

        $arrayIdListIdSection = [];
        while ($row = $resultSelectIdListsIdSections->fetch_assoc()) {
            $arrayIdListIdSection [] = [
                'idList' => $row['id_list_class_section_cancellation_exceptional'],
                'idSection' => $row['id_class_section']
            ];
        }

        $errors = [];
        $registredSectionRespond = [];
        $enrollmentUpdate = [];
        $queryInsertRespondList = "INSERT INTO `ResolutionListClassSectionCancellationExceptional` (id_academic_coordinator, id_list_class_section_cancellation_exceptional, resolution_request_student, date_resolution)
        VALUES (?, ?, ?, CURRENT_DATE());";
        $queryUpdateEnrollment = "UPDATE `EnrollmentClassSections` SET status_enrollment_class_sections = ? WHERE id_student = ? AND id_class_section = ?;";
        foreach($arrayClassSectionIdResolution as $classSectionResolution) {
            for ($i=0; $i < count($arrayIdListIdSection); $i++) { 
                if (in_array($classSectionResolution['idSection'], $arrayIdListIdSection[$i])) {
                    $resultRespondListSection = $this->connection->execute_query($queryInsertRespondList, [$idAcademicCoordinator, $arrayIdListIdSection[$i]['idList'], $classSectionResolution['resolution']]);

                    while ($this->connection->more_results()) {
                        $this->connection->next_result();
                    }

                    if($resultRespondListSection) {
                        $registredSectionRespond [] = 'Se registro el dictamen de la seccion con codigo '.$arrayIdListIdSection[$i]['idSection'];

                        $newStatusEnrollment = ($classSectionResolution['resolution'] === 1) ? 0 : 1;
                        $resultUpdateEnrollment = $this->connection->execute_query($queryUpdateEnrollment, [$newStatusEnrollment, $idStudent, $arrayIdListIdSection[$i]['idSection']]);

                        if ($resultUpdateEnrollment) {
                            if ($newStatusEnrollment == 0) {
                                $enrollmentUpdate[] = 'Seccion con codigo '.$arrayIdListIdSection[$i]['idSection'].' ha sido cancelada para el estudiante con numero de cuenta '.$idStudent;
                            }
                        } else {
                            $errors [] = 'No se actualizo el estado de matricula en la seccion '.$arrayIdListIdSection[$i]['idSection'];
                        }
                    } else {
                        $errors [] = 'No se registro el dictamen de la seccion '.$arrayIdListIdSection[$i]['idSection'];
                    }
                }
            }
        }

        return $response = [
            'status' => 'success',
            'message' => 'Dictamen de solicitud registrado.',
            'registredSectionRespond' => $registredSectionRespond,
            'enrollmentUpdate' => $enrollmentUpdate,
            'errors' => $errors
        ];

    }

    /**
     * 
     */
    public function getAcademicHistoryOfAllStudents (int $idProfessor) {
        if (!(isset($idProfessor))) {
            return $response = [
                'status' => 'warning',
                'message' => 'Numero de empleado docente no definido o nulo.'
            ];
        }

        $querySearchDepartmentHead = "SELECT `Roles`.role FROM `UsersProfessors`
        INNER JOIN `RolesUsersProfessor` ON `UsersProfessors`.id_user_professor = `RolesUsersProfessor`.id_user_professor
        INNER JOIN `Roles` ON `RolesUsersProfessor`.id_role_professor = `Roles`.id_role
        WHERE `UsersProfessors`.username_user_professor = ?;";
        $stmtSearchDepartmentHead = $this->connection->prepare($querySearchDepartmentHead);
        $stmtSearchDepartmentHead->bind_param('i', $idProfessor);
        $stmtSearchDepartmentHead->execute();
        $resultSearchDepartmentHead = $stmtSearchDepartmentHead->get_result();
        $roles = [];

        while ($row = $resultSearchDepartmentHead->fetch_assoc()) {
            $roles [] = $row['role'];
        }

        if (in_array('Department Head', $roles)) {
            
        } else {
            return $response = [
                'status' => 'info',
                'message' => 'El usuario docente no tiene rol de jefe de departamento.'
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
        `AcademicSchedules`.end_timeof_classes as hf, `Classrooms`.name_classroom 
        FROM `ClassSections`
        INNER JOIN classes ON `ClassSections`.id_class = classes.id_class
        INNER JOIN `ClassSectionsDays` ON `ClassSections`.id_class_section = `ClassSectionsDays`.id_class_section
        INNER JOIN `AcademicSchedules` ON `ClassSections`.id_academic_schedules = `AcademicSchedules`.id_academic_schedules
        INNER JOIN `Professors` ON `ClassSections`.id_professor_class_section = `Professors`.id_professor
        INNER JOIN  `Classrooms` ON `ClassSections`.id_classroom_class_section = `Classrooms`.id_classroom
        WHERE `Professors`.id_professor = ? AND `ClassSections`.status_class_section=1;";
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
                        'name_classroom' =>$row['name_classroom']
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
     * @author @AngelNolasco
     * @created 09/12/2024
     */
    public function setUrlVideoClass (int $idProfessor, string $urlVideo, int $idClassSection) {
        if (!(isset($idProfessor) && isset($urlVideo) && isset($idClassSection))) {
            return $response = [
                'status' => 'warning',
                'message' => 'Parametros no definidos o nulos.'
            ];
        }
        
        $querySearchProfessorClassSection = "SELECT * FROM `ClassSections` WHERE id_class_section = ? AND id_professor_class_section = ?;";
        $stmtSearchProfessorClassSection = $this->connection->prepare($querySearchProfessorClassSection);
        $stmtSearchProfessorClassSection->bind_param('ii', $idClassSection, $idProfessor);
        $stmtSearchProfessorClassSection->execute();
        $resultSearchProfessorClassSection = $stmtSearchProfessorClassSection->get_result();

        if ($resultSearchProfessorClassSection->num_rows > 0) {
            $queryCheckExistUrl = "SELECT class_presentation_video FROM `ClassSectionsProfessor` WHERE id_class_section = ?;";
            $stmtCheckExistUrl = $this->connection->prepare($queryCheckExistUrl);
            $stmtCheckExistUrl->bind_param('i', $idClassSection);
            $stmtCheckExistUrl->execute();
            $resultCheckExistUrl = $stmtCheckExistUrl->get_result();

            if ($resultCheckExistUrl->num_rows > 0) {
                $queryUpdateUrlVideo = "UPDATE `ClassSectionsProfessor` SET class_presentation_video = ? WHERE id_class_section = ?;";
                $stmtUpdateUrlVideo = $this->connection->prepare($queryUpdateUrlVideo);
                $stmtUpdateUrlVideo->bind_param('si', $urlVideo, $idClassSection);
    
                if ($stmtUpdateUrlVideo->execute()) {
                    return $response = [
                        'status' => 'success',
                        'message' => 'URL actualizada.'
                    ];
                } else {
                    return $response = [
                        'status' => 'error',
                        'message' => 'No se actualizo la URL.'
                    ];
                }
            } else {
                $queryInsertUrlVideo = "INSERT INTO `ClassSectionsProfessor` (class_presentation_video, id_class_section, status_class_section_professor) VALUES (?, ?, TRUE);";
                $stmtInsertUrlVideo = $this->connection->prepare($queryInsertUrlVideo);
                $stmtInsertUrlVideo->bind_param('si', $urlVideo, $idClassSection);

                if ($stmtInsertUrlVideo->execute()) {
                    return $response = [
                        'status' => 'success',
                        'message' => 'URL registrada.'
                    ];
                } else {
                    return $response = [
                        'status' => 'error',
                        'message' => 'No se registro la URL.'
                    ];
                }
            }

        } else {
            return $response = [
                'status' => 'warning',
                'message' => 'No se encontro la seccion indicada con el maestro indicado.'
            ];
        }
    }

    /**
     * @author @AngelNolasco
     * @created 10/12/2024
     */
    public function getStudentsBySectionCSV (int $idSectionClass) {
        $queryGetStudents = "SELECT `Students`.id_student,
        TRIM(REPLACE(
            CONCAT(
                COALESCE(`Students`.first_name_student, ''),
                ' ',
                COALESCE(`Students`.second_name_student, ''),
                ' ',
                COALESCE(`Students`.third_name_student, ''),
                ' ',
                COALESCE(`Students`.first_lastname_student, ''),
                ' ',
                COALESCE(`Students`.second_lastname_student, '')
            ),
            '  ', ''
        )) as name_student, `Students`.email_student FROM `EnrollmentClassSections`
        INNER JOIN `ClassSections` ON `EnrollmentClassSections`.id_class_section = `ClassSections`.id_class_section
        INNER JOIN `Students` ON `EnrollmentClassSections`.id_student = `Students`.id_student
        WHERE `EnrollmentClassSections`.id_class_section = ?;";

        $resulGetStudents = $this->connection->execute_query($queryGetStudents, [$idSectionClass]);

        if (!$resulGetStudents) {
            return $response = [
                'status' => 'error',
                'message' => 'No se pudieron obtener los estudiantes de la seccion con codigo '.$idSectionClass
            ];
        }

        $students = [];
        while ($row = $resulGetStudents->fetch_assoc()) {
            $students[] = [
                'idStudent' => $row['id_student'],
                'nameStudent' => $row['name_student'],
                'emailStudent' => $row['email_student']
            ]; 
        }

        if (empty($students)) {
            return [
                'status' => 'error',
                'message' => 'No hay estudiantes registrados en la seccion con codigo '.$idSectionClass
            ];
        }

        $filename = "students_section_{$idSectionClass}.csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
    
        $output = fopen('php://output', 'w');
        fputcsv($output, ['CUENTA', 'NOMBRE_COMPLETO', 'CORREO_ELECTRONICO']);

        foreach ($students as $student) {
            fputcsv($output, [$student['idStudent'], $student['nameStudent'], $student['emailStudent']]);
        }
    
        fclose($output);
        exit;
    }

    /**
     * @author @AngelNolasco
     * @created 10/12/2024
     */
    public function getStudentsBySectionPDF (int $idSectionClass) {
        $queryGetStudents = "SELECT `Students`.id_student,
        TRIM(REPLACE(
            CONCAT(
                COALESCE(`Students`.first_name_student, ''),
                ' ',
                COALESCE(`Students`.second_name_student, ''),
                ' ',
                COALESCE(`Students`.third_name_student, ''),
                ' ',
                COALESCE(`Students`.first_lastname_student, ''),
                ' ',
                COALESCE(`Students`.second_lastname_student, '')
            ),
            '  ', ' '
        )) as name_student, `Students`.email_student FROM `EnrollmentClassSections`
        INNER JOIN `ClassSections` ON `EnrollmentClassSections`.id_class_section = `ClassSections`.id_class_section
        INNER JOIN `Students` ON `EnrollmentClassSections`.id_student = `Students`.id_student
        WHERE `EnrollmentClassSections`.id_class_section = ?;";

        $resulGetStudents = $this->connection->execute_query($queryGetStudents, [$idSectionClass]);

        if (!$resulGetStudents) {
            return $response = [
                'status' => 'error',
                'message' => 'No se pudieron obtener los estudiantes de la seccion con codigo '.$idSectionClass
            ];
        }

        $students = [];
        while ($row = $resulGetStudents->fetch_assoc()) {
            $students[] = [
                'idStudent' => $row['id_student'],
                'nameStudent' => $row['name_student'],
                'emailStudent' => $row['email_student']
            ]; 
        }

        if (empty($students)) {
            return [
                'status' => 'error',
                'message' => 'No hay estudiantes registrados en la seccion con codigo '.$idSectionClass
            ];
        }

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);

        // Título
        $pdf->Cell(0, 10, $this->replaceSpecialChars('Estudiantes de la Sección ') . $idSectionClass, 0, 1, 'C');
        $pdf->Ln(10);

        // Encabezados de la tabla
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(30, 10, 'ID', 1, 0, 'C');
        $pdf->Cell(80, 10, 'Nombre Completo', 1, 0, 'C');
        $pdf->Cell(80, 10, $this->replaceSpecialChars('Correo Electrónico'), 1, 1, 'C');

        // Datos de la tabla
        $pdf->SetFont('Arial', '', 10);
        foreach ($students as $student) {
            $pdf->Cell(30, 10, $student['idStudent'], 1, 0, 'C');
            $pdf->Cell(80, 10, $this->replaceSpecialChars($student['nameStudent']), 1, 0, 'L');
            $pdf->Cell(80, 10, $student['emailStudent'], 1, 1, 'L');
        }

        // Forzar descarga del PDF
        $pdf->Output('D', 'students_section_' . $idSectionClass . '.pdf');
        exit;
    }

    /**
     * @author @AngelNolasco
     * @created 10/12/2024
     */
    public function replaceSpecialChars($string) {
        $search = ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ'];
        $replace = ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N'];
        return str_replace($search, $replace, $string);
    }

    /**
     * 
     */
    public function getReportsByPeriod (int $idProfessor) {
        
    }
}
?>