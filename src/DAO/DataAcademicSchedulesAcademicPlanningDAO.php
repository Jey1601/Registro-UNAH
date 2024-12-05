<?php


Class  DataAcademicSchedulesAcademicPlanningDAO{
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
            printf("Conexion Fallida en DataAcademicSchedulesAcademicPlanningDAO: %s\n", $error->getMessage());
        }  
    }

    public function getDataAcademicSchedulesAcademicPlanning()
    {
        try {
            $result = $this->connection->execute_query("CALL GetActiveSchedules()");
        
            if ($result) {
                if ($result->num_rows > 0) {
                    $schedules = [];
                    while ($row = $result->fetch_assoc()) {
                        $schedules[] = [
                            "id_academic_schedule" => $row['id_academic_schedules'],
                            "start_time" => $row['start_timeof_classes'],
                            "end_time" => $row['end_timeof_classes']
                        ];
                    }
                    return [
                        "status" => "success",
                        "data" => $schedules
                    ];
                } else {
                    return [
                        "status" => "warning",
                        "message" => "No se encontraron horarios académicos activos."
                    ];
                }
            } else {
                return [
                    "status" => "error",
                    "message" => "Error en el procedimiento GetActiveSchedules: " . $this->connection->error
                ];
            }
        } catch (Exception $exception) {
            return [
                "status" => "error",
                "message" => "Excepción capturada en getDataAcademicSchedulesAcademicPlanning: " . $exception->getMessage(),
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