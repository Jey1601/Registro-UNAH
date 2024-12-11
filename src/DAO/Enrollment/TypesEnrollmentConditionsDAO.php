<?php


Class TypesEnrollmentConditionsDAO{
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
            printf("Conexion Fallida en TypesEnrollmentConditionsDAO: %s\n", $error->getMessage());
        }  
    }
    
    /**
     * Obtiene los detalles de las condiciones de inscripción para un ID específico de tipo de inscripción.
     *
     * Este método ejecuta el procedimiento almacenado `GetEnrollmentConditionDetails` 
     * y devuelve los resultados mapeados correctamente. Incluye manejo de excepciones adecuado 
     * y verifica que los parámetros de entrada sean válidos.
     *
     * @param int $id_type_enrollment_conditions ID del tipo de condiciones de inscripción.
     * 
     * @return array Un arreglo con los detalles del tipo de inscripción, cada uno como un arreglo asociativo. Y el mensaje de éxito.
     * 
     * @throws InvalidArgumentException Si el parámetro no es un entero válido.
     * @throws mysqli_sql_exception Si ocurre un error en la ejecución de la consulta SQL.
     * @author Alejandro Moya 20211020462
     * @created 09/12/2024
     */
    public function getEnrollmentConditionDetailsById($id_type_enrollment_conditions) {
        if (!is_int($id_type_enrollment_conditions) || $id_type_enrollment_conditions <= 0) {
            throw new InvalidArgumentException("Parámetro de ID inválido en: getEnrollmentConditionDetailsById().");
        }

        try {
            $stmt = $this->connection->prepare("CALL GET_ENROLLMENT_CONDITION_DETAILS(?)");
            if ($stmt === false) {
                throw new mysqli_sql_exception("Error al preparar la consulta: " . $this->connection->error);
            }
            $stmt->bind_param("i", $id_type_enrollment_conditions);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result === false) {
                throw new mysqli_sql_exception("Error al obtener el resultado en GetEnrollmentConditionDetailsById: " . $stmt->error);
            }
            $enrollment_condition_details = [];
            while ($row = $result->fetch_assoc()) {
                $enrollment_condition_details[] = [
                    'id_type_enrollment_conditions' => $row['id_type_enrollment_conditions'],
                    'maximum_student_global_average' => $row['maximum_student_global_average'],
                    'minimum_student_global_average' => $row['minimum_student_global_average'],
                    'status_student_global_average' => $row['status_student_global_average'],
                    'maximum_student_period_average' => $row['maximum_student_period_average'],
                    'minimum_student_period_average' => $row['minimum_student_period_average']
                ];
            }

            $stmt->close();

            return [
                "status" => "success",
                "data" => $enrollment_condition_details
            ];
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception("Error al ejecutar el procedimiento almacenado GET_ENROLLMENT_CONDITION_DETAILS(): " . $e->getMessage());
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