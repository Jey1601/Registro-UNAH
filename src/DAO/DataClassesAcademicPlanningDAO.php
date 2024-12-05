<?php


Class  DataClassesAcademicPlanningDAO{
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
            printf("Conexion Fallida en DataAcademicPlanningDAO: %s\n", $error->getMessage());
        }  
    }

    public function getClassesAcademicPlanningByUndergraduate($idUndergraduate, $academicPeriodicity)
    {
        try {
            // Ejecutar el procedimiento almacenado con los parámetros proporcionados
            $result = $this->connection->execute_query("CALL GetNonServiceClassesByCareer(?, ?)", [
                $idUndergraduate,
                $academicPeriodicity
            ]);
    
            if ($result) {
                if ($result->num_rows > 0) {
                    $classes = [];
                    while ($row = $result->fetch_assoc()) {
                        $classes[] = [
                            "id_class" => $row['id_class'],
                            "name_class" => $row['name_class']
                        ];
                    }
                    return [
                        "status" => "success",
                        "data" => $classes
                    ];
                } else {
                    return [
                        "status" => "warning",
                        "message" => "No se encontraron clases no de servicio para la carrera y periodicidad académica especificadas."
                    ];
                }
            } else {
                return [
                    "status" => "error",
                    "message" => "Error en el procedimiento GetNonServiceClassesByCareer: " . $this->connection->error
                ];
            }
        } catch (Exception $exception) {
            return [
                "status" => "error",
                "message" => "Excepción capturada en getClassesAcademicPlanningByUndergraduate: " . $exception->getMessage(),
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