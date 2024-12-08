<?php


Class DataProfessorsAcademicPlanningDAO{
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
            printf("Conexion Fallida enDataProfessorsAcademicPlanningDAO: %s\n", $error->getMessage());
        }  
    }
    public function getDataProfessorsAcademicPlanning($regionalCenter, $username_user_professor,  $days, $startTime, $endTime){ 
        try {

          // Días, hora de inicio y hora de finalización
            //$days = ['Lunes']; 
            //$startTime = '09:00:00'; 
            //$endTime = '10:00:00'; 
            $WorkingHours = $this->connection->execute_query("CALL GetWorkingHoursActive()");
            $matchingWorkingHours = [];
            while ($row = $WorkingHours->fetch_assoc()) {
                $dbDay = $row['day_week_working_hour'];
                $dbStartTime = $row['check_in_time_working_hour'];
                $dbEndTime = $row['check_out_time_working_hour'];
                $idWorkingHour = $row['id_working_hour'];
                if (in_array($dbDay, $days)) {
                    if (($startTime >= $dbStartTime && $startTime <= $dbEndTime) || 
                        ($endTime >= $dbStartTime && $endTime <= $dbEndTime) ||
                        ($startTime <= $dbStartTime && $endTime >= $dbEndTime)) {
                        if (!isset($matchingWorkingHours[$dbDay])) {
                            $matchingWorkingHours[$dbDay] = [];
                        }
                        $matchingWorkingHours[$dbDay][] = $idWorkingHour;
                    }
                }
            }
            $numberOfDays = count($matchingWorkingHours);
            
            $result = $this->connection->execute_query("CALL GetProfessorsAssignedToRegionalCenter($regionalCenter, $username_user_professor)");
            if ($result) {
                if ($result->num_rows > 0) {
                    $data = [];
                    while ($row = $result->fetch_assoc()) {
                        $idProfessor = $row['id_professor'];
                        $numberOfDaysProfessor = 0;
                        foreach ($days as $day) {
                            $ProfessorWorkingHours = $this->connection->execute_query("CALL GetProfessorWorkingHoursByDay($idProfessor,'$day')");
                            if ($ProfessorWorkingHours) {
                                if ($ProfessorWorkingHours->num_rows > 0) {
                                    while ($Workinghour = $ProfessorWorkingHours->fetch_assoc()) {
                                        $SpecificWorkingHours = $Workinghour['id_working_hour'];
                                        if (isset($matchingWorkingHours[$day])) {
                                            $dayIds = $matchingWorkingHours[$day];
                                            if (in_array($SpecificWorkingHours, $dayIds)) {
                                                $numberOfDaysProfessor = $numberOfDaysProfessor +1; 
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                            if($numberOfDaysProfessor== $numberOfDays){
                                $data[] = [
                                "id_professor" => $row['id_professor'],
                                "first_name" => $row['first_name_professor'],
                                "second_name" => $row['second_name_professor'],
                                "third_name" => $row['third_name_professor'],
                                "first_lastname" => $row['first_lastname_professor'],
                                "second_lastname" => $row['second_lastname_professor'],
                                //"email" => $row['email_professor'],
                                //"status" => $row['status_professor']
                                ];
                            }
                    }
                    return [
                        "status" => "success",
                        "data" => $data
                    ];
                } else {
                    return [
                        "status" => "warning",
                        "message" => "No se encontraron maestros para los parámetros especificados."
                    ];
                }
            } else {
                return [
                    "status" => "error",
                    "message" => "Error al ejecutar el procedimiento almacenado: GetProfessorsAssignedToRegionalCenter()" . $this->connection->error
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