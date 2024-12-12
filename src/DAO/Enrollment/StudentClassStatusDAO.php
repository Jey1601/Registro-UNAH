<?php


Class  StudentClassStatusDAO{
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
            printf("Conexion Fallida en StudentClassStatusDAO: %s\n", $error->getMessage());
        }  
    }
    
    /**
     * Obtiene las clases pendientes de aprobación para un estudiante y las agrupa por departamento.
     * Destinado para el proceso de matricula.
     *
     * Este método ejecuta el procedimiento almacenado `GET_PENDING_CLASSES_BY_STUDENT` 
     * y devuelve los resultados mapeados correctamente. Incluye manejo de excepciones adecuado 
     * y verifica que los parámetros de entrada sean válidos.
     *
     * @param string $student_id ID del estudiante.
     * 
     * @return array Un arreglo con las clases pendientes, cada una como un arreglo asociativo. Y el mensaje de exito.
     * 
     * @throws InvalidArgumentException Si el parámetro no es una cadena válida.
     * @throws mysqli_sql_exception Si ocurre un error en la ejecución de la consulta SQL.
     * @author Alejandro Moya 20211020462
     * @created 08/12/2024
     */
    public function getPendingClassesByStudent($student_id) {
        if (!is_string($student_id) || empty($student_id)) {
            throw new InvalidArgumentException("Parámetro inválido en: getPendingClassesByStudent().");
        }

        try {
            $stmt = $this->connection->prepare("CALL GET_PENDING_CLASSES_BY_STUDENT(?)");
            if ($stmt === false) {
                throw new mysqli_sql_exception("Error al preparar la consulta: " . $this->connection->error);
            }
            $stmt->bind_param("s", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result === false) {
                throw new mysqli_sql_exception("Error al obtener el resultado en GET_PENDING_CLASSES_BY_STUDENT(): " . $stmt->error);
            }
            
            $classes = [];
            $classes_by_department = [];

            while ($row = $result->fetch_assoc()) {
                $department_name = $row['department_name'];
                if (!isset($classes_by_department[$department_name])) {
                    $classes_by_department[$department_name] = [];
                }
                $classes_by_department[$department_name][] = [
                    'ClassID' => $row['id_class'],
                    'ClassName' => $row['class_name']
                ];
            }            
            $stmt->close(); 
            return [
                "status" => "success",
                "data" => $classes_by_department
            ];
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception("Error al ejecutar el procedimiento almacenado GET_PENDING_CLASSES_BY_STUDENT(): " . $e->getMessage());
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
