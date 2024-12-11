<?php


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
        // Validar parámetros de entrada
        if (empty($studentId) ||  $classSectionId <= 0) {
            throw new InvalidArgumentException('Los parámetros proporcionados no son válidos.');
        }

        try {
            // Verificar si ya existe un registro con los mismos datos
            $checkQuery = "CALL GET_ENROLLMENT_STATUS(?, ?);";
            $checkStmt = $this->connection->prepare($checkQuery);
            $checkStmt->bind_param('si', $studentId, $classSectionId);

            // Ejecutar la consulta de verificación
            if (!$checkStmt->execute()) {
                throw new mysqli_sql_exception('Error al ejecutar el procedimiento almacenado para verificación.');
            }

            // Obtener el resultado
            $checkResult = $checkStmt->get_result();
            if ($checkResult->num_rows > 0) {
                // Ya existe un registro
                $checkResult->close();
                $checkStmt->close();
                return [
                    'success' => false,
                    'message' => 'Ya posee esta clase matriculada'
                ];
            }

            // Cerrar el statement de verificación
            $checkResult->close();
            $checkStmt->close();
            $status = 1; 
            // Preparar la llamada al procedimiento almacenado
            $query = "CALL INSERT_ENROLLMENT_CLASS_SECTION(?, ?, ?);";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('sii', $studentId, $classSectionId, $status);

            // Ejecutar la consulta
            if (!$stmt->execute()) {
                throw new mysqli_sql_exception('Error al ejecutar el procedimiento almacenado.');
            }

            // Cerrar el statement
            $stmt->close();

            // Liberar resultados extra (si los hay)
            while ($this->connection->more_results() && $this->connection->next_result()) {
                $extraResult = $this->connection->store_result();
                if ($extraResult) {
                    $extraResult->free();
                }
            }

            return [
                'success' => true,
                'message' => 'Inscripción realizada exitosamente.'
            ];
        } catch (mysqli_sql_exception $e) {
            // Manejo de excepciones de MySQL
            return [
                'success' => false,
                'message' => 'Error en la inscripción: ' . $e->getMessage()
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
                'success' => true,
                'student_count' => (int)$studentCount
            ];
        } catch (mysqli_sql_exception $e) {
            return [
                'success' => false,
                'message' => 'Error al obtener el número de estudiantes: ' . $e->getMessage()
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