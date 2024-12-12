<?php


Class WaitingListsClassSectionsDAO{
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
            printf("Conexion Fallida en WaitingListsClassSectionsDAO: %s\n", $error->getMessage());
        }  
    }

    /**
     * Crea un nuevo registro en la lista de espera de una sección de clase para un estudiante.
     *
     * Este método llama al procedimiento almacenado `InsertIntoWaitingListsClassSections` para realizar la operación.
     * Incluye validación de parámetros y manejo de errores.
     *
     * @param string $studentId ID del estudiante (formato VARCHAR(13)).
     * @param int $classSectionId ID de la sección de clase.
     * 
     * @return array Respuesta con información sobre el éxito o fallo de la operación.
     * 
     * @throws InvalidArgumentException Si los parámetros no son válidos.
     * @throws mysqli_sql_exception Si ocurre un error en la ejecución de la consulta SQL.
     * @author Alejandro Moya 20211020462
     * @created 08/12/2024
     */
    public function insertWaitingListClassSection(string $studentId, int $classSectionId){
        // Validar parámetros de entrada
        if (empty($studentId)  || $classSectionId <= 0) {
            throw new InvalidArgumentException('Los parámetros proporcionados no son válidos.');
        }

        try {
            // Preparar la llamada al procedimiento almacenado
            $query = "CALL INSERT_WAITING_LIST_CLASS_SECTIONS(?, ?);";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('si', $studentId, $classSectionId); // 'si' para string e integer

            // Ejecutar la consulta
            if (!$stmt->execute()) {
                throw new mysqli_sql_exception('Error al ejecutar el procedimiento almacenado INSERT_WAITING_LIST_CLASS_SECTIONS.');
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
                'message' => 'Estudiante agregado a la lista de espera de la sección de clase exitosamente.'
            ];
        } catch (mysqli_sql_exception $e) {
            // Manejo de excepciones de MySQL
            return [
                'success' => false,
                'message' => 'Error al agregar el estudiante: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtiene el número de registros asociados a una sección de clase específica.
     *
     * Este método llama al procedimiento almacenado `GetTupleCountByIdClassSection` para realizar la operación.
     * Valida los parámetros y maneja errores durante la ejecución.
     *
     * @param int $classSectionId ID de la sección de clase.
     * 
     * @return array Respuesta con el número de tuplas o un mensaje de error.
     * 
     * @throws InvalidArgumentException Si el parámetro no es válido.
     * @throws mysqli_sql_exception Si ocurre un error en la ejecución de la consulta SQL.
     * @author Alejandro Moya 20211020462
     * @created 09/12/2024
     */
    public function getTupleCountByClassSection(int $classSectionId) {
        if (!is_int($classSectionId)) {
            throw new InvalidArgumentException("Parámetros inválidos en: getTupleCountByClassSection().");
        }
        try {
            $query = "CALL GET_COUNT_WAITING_LIST_CLASS_SECTION(?, @tuple_count);";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('i', $classSectionId);
            if (!$stmt->execute()) {
                throw new mysqli_sql_exception('Error al ejecutar el procedimiento almacenado GET_COUNT_WAITING_LIST_CLASS_SECTION().');
            }
            $stmt->close();
            $resultQuery = "SELECT @tuple_count AS tuple_count;";
            $result = $this->connection->query($resultQuery);
            $row = $result->fetch_assoc();
            $tupleCount = $row['tuple_count'] ?? 0;
            return [
                'status' => 'success',
                'tuple_count' => (int)$tupleCount
            ];
        } catch (mysqli_sql_exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al obtener el número de tuplas: ' . $e->getMessage()
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