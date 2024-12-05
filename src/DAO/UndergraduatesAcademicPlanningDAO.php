<?php


Class  UndergraduatesAcademicPlanningDAO{
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
            printf("Conexion Fallida en UndergraduatesAcademicPlanningDAO: %s\n", $error->getMessage());
        }  
    }

    public function getUndergraduatesByRegionalCentersAndDepartmentHead($regionalCenter, $username_user_professor) {
        try {
            $result = $this->connection->execute_query("CALL GetUndergraduatesByProfessorAndRegionalCenter($username_user_professor,$regionalCenter)");
    
            if ($result) {
                if ($result->num_rows > 0) {
                    $undergraduates = [];
                    while ($row = $result->fetch_assoc()) {
                        if (isset($row['id_undergraduate']) && isset($row['name_undergraduate'])) {
                            $undergraduates[] = [
                                "id_undergraduate" => $row['id_undergraduate'],
                                "name_undergraduate" => $row['name_undergraduate']
                            ];
                        }
                    }
                    return [
                        "status" => "success",
                        "data" => $undergraduates
                    ];
                } else {
                    // No se encontraron registros
                    return [
                        "status" => "warning",
                        "message" => "No se encontraron carreras asociadas al profesor y centro regional dados."
                    ];
                }
            } else {
                // Error en la ejecución del procedimiento almacenado
                return [
                    "status" => "error",
                    "message" => "Error en el procedimiento GetUndergraduatesByProfessorAndRegionalCenter: " . $this->connection->error
                ];
            }
        } catch (Exception $exception) {
            // Manejo de excepciones
            return [
                "status" => "error",
                "message" => "Excepción capturada en getUndergraduatesByRegionalCentersAndDepartmentHead: " . $exception->getMessage(),
                "code" => $exception->getCode()
            ];
        }
    }
    


    // Método para cerrar la conexión (opcional)
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

}
?>