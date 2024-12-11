<?php


Class StudentGradesAveragesDAO{
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
     * Obtiene los distintos promedios del estudiante en la universidad. 
     *
     * Este método ejecuta el procedimiento almacenado `GetStudentGradesAverages` 
     * y devuelve los resultados mapeados correctamente. Incluye manejo de excepciones adecuado 
     * y verifica que los parámetros de entrada sean válidos.
     *
     * @param string $student_id ID del estudiante (formato 'ID_ESTUDIANTE').
     * 
     * @return array Un arreglo con las calificaciones promedio del estudiante, cada una como un arreglo asociativo. Y el mensaje de éxito.
     * 
     * @throws InvalidArgumentException Si el parámetro no es una cadena válida para el ID del estudiante.
     * @throws mysqli_sql_exception Si ocurre un error en la ejecución de la consulta SQL.
     * @author Alejandro Moya 20211020462
     * @created 09/12/2024
     */
    public function getStudentGradesAverages($student_id) {
        if (empty($student_id) || !is_string($student_id)) {
            throw new InvalidArgumentException("Parámetro de ID de estudiante inválido en: getStudentGradesAverages().");
        }
        
        try {
            $stmt = $this->connection->prepare("CALL GET_STUDENT_GRADES_AVERAGES(?)");
            if ($stmt === false) {
                throw new mysqli_sql_exception("Error al preparar la consulta: " . $this->connection->error);
            }
            $stmt->bind_param("s", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result === false) {
                throw new mysqli_sql_exception("Error al obtener el resultado en GetStudentGradesAverages: " . $stmt->error);
            }
            $grades_averages = [];
            while ($row = $result->fetch_assoc()) {
                $grades_averages[] = [
                    'id_student_grades_averages' => $row['id_student_grades_averages'],
                    'id_student' => $row['id_student'],
                    'global_grade_average_student' => $row['global_grade_average_student'],
                    'period_grade_average_student' => $row['period_grade_average_student'],
                    'annual_academic_grade_average_student' => $row['annual_academic_grade_average_student']
                ];
            }

            $stmt->close();

            // Retornar los datos mapeados
            return [
                "status" => "success",
                "data" => $grades_averages
            ];
            
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception("Error al ejecutar el procedimiento almacenado GET_STUDENT_GRADES_AVERAGES(): " . $e->getMessage());
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