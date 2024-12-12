<?php

include_once 'EnrollmentProcessDAO.php';
Class DatesEnrollmentProcessDAO{
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
            printf("Conexion Fallida en DatesEnrollmentProcessDAO: %s\n", $error->getMessage());
        }  
    }
    
    /**
     * Obtiene los procesos de inscripción disponibles para una fecha dada.
     *
     * Este método ejecuta el procedimiento almacenado `GET_ENROLLMENT_PROCESS_BY_DATE` 
     *
     * @param string $input_date Fecha del proceso de inscripción (formato 'YYYY-MM-DD').
     * 
     * @return array Un arreglo con los procesos de inscripción, cada uno como un arreglo asociativo. Y el mensaje de éxito.
     * 
     * @throws InvalidArgumentException Si el parámetro no es una cadena con formato de fecha válido.
     * @throws mysqli_sql_exception Si ocurre un error en la ejecución de la consulta SQL.
     * @author Alejandro Moya 20211020462
     * @created 09/12/2024
     */
    public function getEnrollmentProcessByDate() {
        $Date = new DateTime();
        $actualDate = $Date->format('Y-m-d');
        
        try {
            $stmt = $this->connection->prepare("CALL GET_ENROLLMENT_PROCESS_BY_DATE(?)");
            if ($stmt === false) {
                throw new mysqli_sql_exception("Error al preparar la consulta: " . $this->connection->error);
            }
            $stmt->bind_param("s",  $actualDate);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result === false) {
                throw new mysqli_sql_exception("Error al obtener el resultado en GetEnrollmentProcessByDate: " . $stmt->error);
            }
            $enrollment_processes = [];
            while ($row = $result->fetch_assoc()) {
                $enrollment_processes[] = [
                    'id_dates_enrollment_process' => $row['id_dates_enrollment_process'],
                    'id_enrollment_process' => $row['id_enrollment_process'],
                    'id_type_enrollment_conditions' => $row['id_type_enrollment_conditions'],
                    'day_available_enrollment_process' => $row['day_available_enrollment_process'],
                    'start_time_available_enrollment_process' => $row['start_time_available_enrollment_process'],
                    'end_time_available_enrollment_process' => $row['end_time_available_enrollment_process'],
                    'status_date_enrollment_process' => $row['status_date_enrollment_process']
                ];
            }
            $stmt->close();
            if (empty($enrollment_processes)) {
                return [
                    "status" => "warning",
                    "message" => "No existen Fechas de Matricula para el dia de hoy."
                ];
            } else {
                return [
                    "status" => "success",
                    "data" => $enrollment_processes
                ];
            } 
            
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception("Error al ejecutar el procedimiento almacenado GET_ENROLLMENT_PROCESS_BY_DATE: " . $e->getMessage());
        }
    }
    
    /**
     * Obtiene el calendario de fechas de matricula disponibles para un proceso de matricula dado.
     *
     * Este método ejecuta el procedimiento almacenado `GET_DATES_BY_ENROLLMENT_PROCESS` 
     *
     * @return array Un arreglo con los procesos de inscripción, cada uno como un arreglo asociativo. Y el mensaje de éxito.
     * 
     * @throws mysqli_sql_exception Si ocurre un error en la ejecución de la consulta SQL.
     * @author Alejandro Moya 20211020462
     * @created 09/12/2024
     */
    public function getEnrollmentProcessByEnrollmentProcess() {

        $EnrollmentProcessDAO = new EnrollmentProcessDAO();
        $enrollment_process_id = $EnrollmentProcessDAO->checkActiveEnrollmentProcess();
        try {
            $stmt = $this->connection->prepare("CALL GET_DATES_BY_ENROLLMENT_PROCESS(?)");
            if ($stmt === false) {
                throw new mysqli_sql_exception("Error al preparar la consulta: " . $this->connection->error);
            }

            $stmt->bind_param("i", $enrollment_process_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result === false) {
                throw new mysqli_sql_exception("Error al obtener el resultado en GET_DATES_BY_ENROLLMENT_PROCESS(): " . $stmt->error);
            }

            $enrollment_processes = [];
            $countDays = 1; 
            while ($row = $result->fetch_assoc()) {
                $key = $row['day_available_enrollment_process'] . ' ' . $row['start_time_available_enrollment_process'] . ' ' . $row['end_time_available_enrollment_process'];
                if (!isset($enrollment_processes[$key])) {
                    $enrollment_processes[$key] = [
                        'day_available_enrollment_process' => $row['day_available_enrollment_process'],
                        'start_time_available_enrollment_process' => $row['start_time_available_enrollment_process'],
                        'end_time_available_enrollment_process' => $row['end_time_available_enrollment_process'],
                        'messages' => ''
                    ];
                }
                $message = '';
                if ($row['status_student_global_average'] == 0) {
                    $message = 'Indice Ultimo Periodo de ' . $row['maximum_student_period_average'] . " a " . $row['minimum_student_period_average'];
                } elseif ($row['status_student_global_average'] == 1) {
                    $message = 'Excelencia Academica. (Indice Global de ' . $row['maximum_student_global_average'] . " a " . $row['minimum_student_global_average'] . ")";
                }
                $enrollment_processes[$key]['messages'] .= $message . ' ,';
            }
            
            $resultado = [];
            foreach ($enrollment_processes as $key => $process) {
                $resultado[] = [
                    'day_available_enrollment_process' => $process['day_available_enrollment_process'],
                    'start_time_available_enrollment_process' => $process['start_time_available_enrollment_process'],
                    'end_time_available_enrollment_process' => $process['end_time_available_enrollment_process'],
                    'message' => trim($process['messages']) 
                ];
            }
            $stmt->close(); 
            if (empty($resultado)) {
                return [
                    "status" => "warning",
                    "message" => "Calendario de Matricula No disponible en este momento."
                ];
            } else {
                return [
                    "status" => "success",
                    "data" => $resultado
                ];
            } 
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception("Error al ejecutar el procedimiento almacenado GetDatesByEnrollmentProcess: " . $e->getMessage());
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