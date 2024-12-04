<?php


Class  AcademicPlanningStatusDAO{
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
            printf("Conexion Fallida en AcademicPlanningStatusDAO: %s\n", $error->getMessage());
        }  
    }

    public function getAcademicPlanning(){
        try {
            $ActiveAcademicPlanning = $this->connection->execute_query("CALL ACTIVE_ACADEMIC_PLANNING()");

            if ($ActiveAcademicPlanning) { 
                if ($ActiveAcademicPlanning->num_rows > 0) { 
                    $ActiveAcademicPlanning= $ActiveAcademicPlanning->fetch_assoc(); 
                    return [
                        "status" => "success",
                        "id_academic_planning" => $ActiveAcademicPlanning['id_academic_planning_process']
                    ];
                } else {
                    return [
                        "status" => "warning",
                        "message" => "No se encontró un proceso de planificación academica activo"
                    ];
                }
            } else {
                return [
                    "status" => "error",
                    "message" => "Error en el procedimiento ACTIVE_ACADEMIC_PLANNING(): " . $this->connection->error
                ];
            }
        } catch (Exception $exception) {
            return [
                "status" => "error",
                "message" => "Excepción capturada en getAcademicPlanning(): " . $exception->getMessage(),
                "code" => $exception->getCode()
            ];
        }
    }

    public function getStartDateAcademicPlanning($idAcademicPlanning){
        try{
            if($idAcademicPlanning){
                $startDate = $this->connection->execute_query("CALL START_DATE_ACADEMIC_PLANNING($idAcademicPlanning)");
                if($startDate){
                    $rowStartDate = $startDate->fetch_assoc();
                    return [
                        "status" => "success",
                        "startDate" => $rowStartDate['start_dateof_academic_planning_process']
                    ];
                }else{
                    return [
                        "status" => "warning",
                        "message" => "No se encontro la fecha deacuerdo al id de admision proporcionado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "message" => "El ID de admision no es válido"
                ];
            }
        }catch (Exception $exception){
            return [
                "status" => "error",
                "message" => "Excepción capturada: " . $exception->getMessage(),
                "code" => $exception->getCode()
            ];
        }
    }

    public function getEndDateAcademicPlanning($idAcademicPlanning){
        try{
            if($idAcademicPlanning){
                $year = date("Y"); //obtener año actual
                $endDate = $this->connection->execute_query("CALL END_DATE_ACADEMIC_PLANNING($idAcademicPlanning)");
                if($endDate){
                    $rowEndDate = $endDate->fetch_assoc();
                    return [
                        "status" => "success",
                        "endDate" => $rowEndDate['end_dateof_academic_planning_process']
                    ];
                }else{
                    return [
                        "status" => "warning",
                        "message" => "No se encontro la fecha deacuerdo al id de admision proporcionado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "message" => "El ID de admision no es válido"
                ];
            }
        }catch (Exception $exception){
            return [
                "status" => "error",
                "message" => "Excepción capturada: " . $exception->getMessage(),
                "code" => $exception->getCode()
            ];
        }
    }

    public function getVerifyAcademicPlanning(){
        $dataidAcademicPlanning = $this->getAcademicPlanning();
        if ( $dataidAcademicPlanning['status'] != 'success') {
            echo  $dataidAcademicPlanning['message'];
            return false;
        } 
        $idAcademicPlanning = $dataidAcademicPlanning['id_academic_planning'];

        $dataStartAcademicPlanning = $this->getStartDateAcademicPlanning($idAcademicPlanning);
        if ( $dataStartAcademicPlanning['status'] != 'success') {
            echo   $dataStartAcademicPlanning['message'];
            return false;
        } 
        $startDateAcademicPlanning =  new DateTime($dataStartAcademicPlanning['startDate']);
     

        $dataEndDateAcademicPlanning = $this->getEndDateAcademicPlanning($idAcademicPlanning);
        if ($dataEndDateAcademicPlanning['status'] != 'success') {
            echo  $dataEndDateAcademicPlanning['message'];
            return false;
        } 
        $endtDateAcademicPlanning =   new DateTime($dataEndDateAcademicPlanning['endDate']);

        $currentDate = new DateTime();
        if ($currentDate >= $startDateAcademicPlanning && $currentDate <= $endtDateAcademicPlanning) {
            return [
                'status' => true,
                'idAcademicPlanning' => $idAcademicPlanning
            ];
        } else {
            return false;
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
