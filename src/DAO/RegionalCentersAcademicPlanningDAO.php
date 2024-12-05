<?php


Class  RegionalCentersAcademicPlanningDAO{
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
            printf("Conexion Fallida en RegionalCentersAcademicPlanningDAO: %s\n", $error->getMessage());
        }  
    }

    public function getRegionalCentersByDepartmentHead($username_user_professor){
    try {
        $result = $this->connection->execute_query("CALL GetRegionalCentersByProfessor($username_user_professor)");

        if ($result) {
            if ($result->num_rows > 0) {
                $regionalCenters = [];
                while ($row = $result->fetch_assoc()) {
                    if (isset($row['id_regionalcenter']) && isset($row['name_regional_center'])) {
                        $regionalCenters[] = [
                            "id_regionalcenter" => $row['id_regionalcenter'],
                            "name_regional_center" => $row['name_regional_center']
                        ];
                    }
                }
                return [
                    "status" => "success",
                    "data" => $regionalCenters
                ];
            } else {
                // No se encontraron registros
                return [
                    "status" => "warning",
                    "message" => "No se encontraron centros regionales asociados al profesor dado."
                ];
            }
        } else {
            // Error en la ejecución del procedimiento almacenado
            return [
                "status" => "error",
                "message" => "Error en el procedimiento GetRegionalCentersByProfessor: " . $this->connection->error
            ];
        }
    } catch (Exception $exception) {
        // Manejo de excepciones
        return [
            "status" => "error",
            "message" => "Excepción capturada en getRegionalCentersByDepartmentHead: " . $exception->getMessage(),
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