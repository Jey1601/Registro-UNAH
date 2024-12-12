<?php
include_once __DIR__ . '/../ClassSectionsDAO.php';
include_once 'WaitingListsClassSectionsDAO.php';


Class EnrollmentClassSectionsDAO{
    private $host = 'localhost';
    private $user = 'root';
    private $password = '12345';
    private $dbName = 'unah_registration';
    private $connection;
   
    public function __construct()
    {
        $this->connection = null;
        try {
            $this->connection = new mysqli($this->host, $this->user, $this->password, $this->dbName);
        } catch (Exception $error) {
            printf("Conexion Fallida enEnrollmentClassSectionsDAO: %s\n", $error->getMessage());
        }  
    }

    /**
     * Crea un nuevo registro de inscripción en una sección de clase para un estudiante.
     *
     * Este método llama al procedimiento almacenado `INSERT_ENROLLMENT_CLASS_SECTION` para realizar la operación. 
     * Incluye validación de parámetros y manejo de errores.
     *
     * @param string $studentId ID del estudiante (formato VARCHAR(13)).
     * @param int $classSectionId ID de la sección de clase.
     * @param bool $status Estado de la inscripción (activo/inactivo).
     * 
     * @return array Respuesta con información sobre el éxito o fallo de la operación.
     * 
     * @throws InvalidArgumentException Si los parámetros no son válidos.
     * @throws mysqli_sql_exception Si ocurre un error en la ejecución de la consulta SQL.
     * @author Alejandro Moya 20211020462
     * @created 08/12/2024
     */
    public function insertEnrollmentClassSection(string $studentId, int $classSectionId){
        if (empty($studentId) ||  $classSectionId <= 0) {
            throw new InvalidArgumentException('Los parámetros proporcionados no son válidos.');
        }

        try {
            //verificar sino tiene la clase ya matriculada.
            $checkQuery = "CALL GET_ENROLLMENT_STATUS(?, ?);";
            $checkStmt = $this->connection->prepare($checkQuery);
            $checkStmt->bind_param('si', $studentId, $classSectionId);
            if (!$checkStmt->execute()) {
                throw new mysqli_sql_exception('Error al ejecutar el procedimiento almacenado para verificación.');
            }
            $checkResult = $checkStmt->get_result();
            if ($checkResult->num_rows > 0) {
                $checkResult->close();
                $checkStmt->close();
                return [
                    'status' => 'warning',
                    'message' => 'Ya posee esta clase matriculada'
                ];
            }
            $checkResult->close();
            $checkStmt->close();
            //verificar si existen cupos
            $studentsEnrollment = $this->getStudentCountByClassSection($classSectionId);
            if($studentsEnrollment['status']=='success'){
                $numberStudentsEnrollment = $studentsEnrollment['student_count'];
                $daoClassSections = new  ClassSectionsDAO();
                $dataSpotsAvailable = $daoClassSections->getAvailableSpots($classSectionId);
                $numberSpotsAvailable = $dataSpotsAvailable['availableSpots'];
                if($numberStudentsEnrollment < $numberSpotsAvailable){
                    $status = 1; 
                    $query = "CALL INSERT_ENROLLMENT_CLASS_SECTION(?, ?, ?);";
                    $stmt = $this->connection->prepare($query);
                    $stmt->bind_param('sii', $studentId, $classSectionId, $status);
                    if (!$stmt->execute()) {
                        throw new mysqli_sql_exception('Error al ejecutar el procedimiento almacenado.');
                    }
                    $stmt->close();
                    while ($this->connection->more_results() && $this->connection->next_result()) {
                        $extraResult = $this->connection->store_result();
                        if ($extraResult) {
                            $extraResult->free();
                        }
                    }
                    return [
                        'status' => 'success',
                        'message' => 'Inscripción realizada exitosamente.'
                    ];
                }else{
                    $WaitingListsClassSectionsDAO = new WaitingListsClassSectionsDAO();
                    $waitingStudents = $WaitingListsClassSectionsDAO->getTupleCountByClassSection($classSectionId);
                    return [
                        'status' => 'warning',
                        'message' => "Seccion en Espera. Cantidad en espera: ".$waitingStudents['tuple_count']
                    ];                    
                }
            }else{
                return $studentsEnrollment;
            }
        } catch (mysqli_sql_exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error en la Matricula: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtiene el número de estudiantes matriculados en una sección de clase específica.
     *
     * Este método llama al procedimiento almacenado `GetStudentCountByClassSection` para realizar la operación.
     * Valida los parámetros y maneja errores durante la ejecución.
     *
     * @param int $classSectionId ID de la sección de clase.
     * 
     * @return array Respuesta con el número de estudiantes matriculados o un mensaje de error.
     * 
     * @throws InvalidArgumentException Si el parámetro no es válido.
     * @throws mysqli_sql_exception Si ocurre un error en la ejecución de la consulta SQL.
     * @author Alejandro Moya 20211020462
     * @created 08/12/2024
     */
    public function getStudentCountByClassSection(int $classSectionId) {
        if (!is_int($classSectionId)) {
            throw new InvalidArgumentException("Parámetros inválidos en: getStudentCountByClassSection().");
        }
        try {
            $query = "CALL GET_STUDENT_COUNT_BY_CLASS_SECTION(?, @student_count);";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('i', $classSectionId);
            if (!$stmt->execute()) {
                throw new mysqli_sql_exception('Error al ejecutar el procedimiento almacenado GET_STUDENT_COUNT_BY_CLASS_SECTION().');
            }
            $stmt->close();
            $resultQuery = "SELECT @student_count AS student_count;";
            $result = $this->connection->query($resultQuery);
            $row = $result->fetch_assoc();
            $studentCount = $row['student_count'] ?? 0;
            return [
                'status' => 'success',
                'student_count' => (int)$studentCount
            ];
        } catch (mysqli_sql_exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al obtener el número de estudiantes: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Actualiza el estado de inscripción de un estudiante en una sección de clase específica.
     *
     * Este método llama al procedimiento almacenado `UpdateEnrollmentStatus` para realizar la operación.
     * Valida los parámetros y maneja errores durante la ejecución.
     *
     * @param string $studentId ID del estudiante.
     * @param int $classSectionId ID de la sección de clase.
     * 
     * @return array Respuesta con el estado de la operación o un mensaje de error.
     * 
     * @throws InvalidArgumentException Si los parámetros no son válidos.
     * @throws mysqli_sql_exception Si ocurre un error en la ejecución de la consulta SQL.
     * @author Alejandro Moya 20211020462
     * @created 09/12/2024
     */
    public function updateEnrollmentStatus(string $studentId, int $classSectionId) {
        if (empty($studentId) || $classSectionId <= 0) {
            throw new InvalidArgumentException("Parámetros inválidos en: updateEnrollmentStatus().");
        }

        try {
            $query = "CALL UPDATE_ENROLLMENT_STATUS(?, ?);";
            $stmt = $this->connection->prepare($query);

            if (!$stmt) {
                throw new mysqli_sql_exception('Error al preparar el procedimiento almacenado.');
            }

            $stmt->bind_param('si', $studentId, $classSectionId);

            if (!$stmt->execute()) {
                throw new mysqli_sql_exception('Error al ejecutar el procedimiento almacenado UPDATE_ENROLLMENT_STATUS().');
            }

            $stmt->close();

            return [
                'status' => 'success',
                'message' => 'El estado de inscripcion se actualizo correctamente.'
            ];
        } catch (mysqli_sql_exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al actualizar el estado de inscripcion: ' . $e->getMessage()
            ];
        }
    }

    // Método para cerrar la conexión (opcional), 
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

}
?>
