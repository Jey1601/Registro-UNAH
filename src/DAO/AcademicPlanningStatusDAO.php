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

    /**
        * Obtiene el proceso de planificación académica activa.
        *
        * Este método ejecuta el procedimiento almacenado `ACTIVE_ACADEMIC_PLANNING()` 
        * para recuperar el proceso de planificación académica activa, si existe.
        *
        * @return array Un arreglo asociativo con la siguiente estructura:
        *               - "status" (string): Indica el resultado de la operación. 
        *                 Valores posibles:
        *                   - "success": Cuando se encuentra un proceso de planificación académica activo.
        *                   - "warning": Cuando no se encuentra ningún proceso de planificación académica activo.
        *                   - "error": Cuando ocurre un error durante la ejecución.
        *               - "id_academic_planning" (int, opcional): ID del proceso activo. Solo se incluye si el estado es "success".
        *               - "message" (string, opcional): Mensaje explicativo en caso de "warning" o "error".
        *               - "code" (int, opcional): Código de excepción en caso de error.
        *
        * @throws Exception Captura y maneja cualquier excepción que ocurra durante la ejecución.
        * @author Alejandro Moya 20211020462
        * @created Noviembre de 2024
        */
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

    /**
        * Obtiene la fecha de inicio de un proceso de planificación académica.
        *
        * Este método ejecuta el procedimiento almacenado `START_DATE_ACADEMIC_PLANNING($idAcademicPlanning)` 
        * para recuperar la fecha de inicio de un proceso de planificación académica basado en un ID proporcionado.
        *
        * @param int $idAcademicPlanning El ID del proceso de planificación académica.
        *
        * @return array Un arreglo asociativo con la siguiente estructura:
        *               - "status" (string): Indica el resultado de la operación. 
        *                 Valores posibles:
        *                   - "success": Cuando se encuentra la fecha de inicio del proceso.
        *                   - "warning": Cuando no se encuentra la fecha basada en el ID proporcionado.
        *                   - "error": Cuando el ID es inválido o ocurre un error durante la ejecución.
        *               - "startDate" (string, opcional): La fecha de inicio del proceso. Solo se incluye si el estado es "success".
        *               - "message" (string, opcional): Mensaje explicativo en caso de "warning" o "error".
        *               - "code" (int, opcional): Código de excepción en caso de error.
        *
        * @throws Exception Captura y maneja cualquier excepción que ocurra durante la ejecución.
        *
        * @author Alejandro Moya 20211020462
        * @created Noviembre de 2024
        */
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

    /**
        * Obtiene la fecha de finalización de un proceso de planificación académica.
        *
        * Este método ejecuta el procedimiento almacenado `END_DATE_ACADEMIC_PLANNING($idAcademicPlanning)` 
        * para recuperar la fecha de finalización de un proceso de planificación académica basado en un ID proporcionado.
        *
        * @param int $idAcademicPlanning El ID del proceso de planificación académica.
        *
        * @return array Un arreglo asociativo con la siguiente estructura:
        *               - "status" (string): Indica el resultado de la operación. 
        *                 Valores posibles:
        *                   - "success": Cuando se encuentra la fecha de finalización del proceso.
        *                   - "warning": Cuando no se encuentra la fecha basada en el ID proporcionado.
        *                   - "error": Cuando el ID es inválido o ocurre un error durante la ejecución.
        *               - "endDate" (string, opcional): La fecha de finalización del proceso. Solo se incluye si el estado es "success".
        *               - "message" (string, opcional): Mensaje explicativo en caso de "warning" o "error".
        *               - "code" (int, opcional): Código de excepción en caso de error.
        *
        * @throws Exception Captura y maneja cualquier excepción que ocurra durante la ejecución.
        *
        * @author Alejandro Moya 20211020462
        * @created Noviembre de 2024
        */
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

    /**
        * Verifica si existe un proceso de planificación académica activo y válido.
        *
        * Este método realiza las siguientes operaciones:
        * 1. Obtiene el proceso de planificación académica activo.
        * 2. Recupera la fecha de inicio y la fecha de finalización del proceso.
        * 3. Comprueba si la fecha actual se encuentra dentro del rango de fechas.
        *
        * @return array|bool Un arreglo asociativo en caso de éxito con la siguiente estructura:
        *                    - "status" (bool): Indica que el proceso de planificación está activo.
        *                    - "idAcademicPlanning" (int): ID del proceso de planificación académica activo.
        *                    Devuelve `false` en caso de fallo o si no hay un proceso activo válido.
        *
        * @throws Exception Maneja cualquier excepción que pueda ocurrir durante el proceso.
        *
        * @author Alejandro Moya 20211020462
        * @created Noviembre de 2024
        */
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

    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

}
?>
