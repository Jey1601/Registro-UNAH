<?php


Class  DataInfrastructureAcademicPlanningDAO{
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
            printf("Conexion Fallida en DataInfrastructureAcademicPlanningDAO: %s\n", $error->getMessage());
        }  
    }
    public function getDataInfrastructureAcademicPlanning($regionalCenter, $username_user_professor)
    {
        try {
            $result = $this->connection->execute_query("CALL GetBuildingsAndClassroomsByProfessor($regionalCenter, $username_user_professor)");
            if ($result) {
                if ($result->num_rows > 0) {
                    $data = [];
                    while ($row = $result->fetch_assoc()) {
                        $data[] = [
                            "id_building" => $row['BuildingID'],  
                            "name_building" => $row['BuildingName'],
                            "id_classroom" => $row['ClassroomID'],  
                            "name_classroom" => $row['ClassroomName']
                        ];
                    }
                    return [
                        "status" => "success",
                        "data" => $data
                    ];
                } else {
                    return [
                        "status" => "warning",
                        "message" => "No se encontraron edificios para los parámetros especificados."
                    ];
                }
            } else {
                return [
                    "status" => "error",
                    "message" => "Error al ejecutar el procedimiento almacenado: " . $this->connection->error
                ];
            }
        } catch (Exception $exception) {
            return [
                "status" => "error",
                "message" => "Excepción capturada: " . $exception->getMessage(),
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