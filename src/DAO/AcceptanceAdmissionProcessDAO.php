<?php
include_once "AdmissionProccessDAO.php";

Class AcceptanceAdmissionProcessDAO{
    private $host = 'localhost';
    private $user = 'root';
    private $password = '12345';
    private $dbName = 'unah_registration';
    private $connection;
   
    public function __construct(){
        $this->connection = null;
        try {
            $this->connection = new mysqli($this->host, $this->user, $this->password, $this->dbName);
        } catch (Exception $error) {
            printf("Failed connection: %s\n", $error->getMessage());
        }
    }
    
    /**
     * Obtiene el ID del proceso de consultar resultados activo ,que pertenece al proceso de admision especificado, desde la base de datos.
     *
     * Este método ejecuta un procedimiento almacenado llamado "ACTIVE_ACCEPTANCE()"
     * y retorna el ID del proceso de consultar resultados activo si existe.
     *
     * @param int $idAdmissionProcess El ID  del proceso de admision para el cual se desea obtener el proceso de consultar resultados activo.
     * 
     * @return array Devuelve un arreglo asociativo con el resultado de la consulta:
     *               - Si se encuentra un proceso activo:
     *                 [
     *                   "status" => "success",
     *                   "id_acceptance_admission_process" => int
     *                 ]
     *               - Si no se encuentra un proceso activo:
     *                 [
     *                   "status" => "not_found",
     *                   "message" => "No se encontraron procesos de consultar resultados activos"
     *                 ]
     *               - Si ocurre un error en la ejecución del procedimiento:
     *                 [
     *                   "status" => "error",
     *                   "message" => string
     *                 ]
     *               - Si ocurre una excepción durante la ejecución del código:
     *                 [
     *                   "status" => "error",
     *                   "message" => string,
     *                   "code" => int
     *                 ]
     * @author Alejandro Moya 20211020462
     * @created Noviembre de 2024
     */
    public function getIdAcceptanceAdmissionProcess($idAdmissionProcess){
        try {
            $idAdmissionProcess = (int) $idAdmissionProcess;
            $IdsAcceptanceAdmissionProcess = $this->connection->execute_query("CALL ACTIVE_ACCEPTANCE($idAdmissionProcess)");

            if ($IdsAcceptanceAdmissionProcess) { 
                if ($IdsAcceptanceAdmissionProcess->num_rows > 0) { 
                    $IdAcceptanceAdmissionProcess= $IdsAcceptanceAdmissionProcess->fetch_assoc();
                    return [
                        "status" => "success",
                        "id_acceptance_admission_process" => $IdAcceptanceAdmissionProcess['id_acceptance_admission_process']
                    ];
                } else {
                    return [
                        "status" => "not_found",
                        "message" => "No se encontraron procesos de consultar resultados activos"
                    ];
                }
            } else {
                return [
                    "status" => "error",
                    "message" => "Error en el procedimiento ACTIVE_ACCEPTANCE(): " . $this->connection->error
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

    /**
     * Obtiene la fecha de inicio del  subproceso de consultar resultados basado en el ID del del subproceso de consultar resultados.
     *
     * Este método consulta la base de datos utilizando una procedimiento  almacenado llamado `START_DATE_ACCEPTANCE()`
     * para obtener la fecha de inicio del subproceso de consultar resultados. Si el ID proporcionado es válido y la consulta se
     * realiza con éxito, se devuelve la fecha de inicio. Si no se encuentra la fecha, se devuelve un mensaje de error
     * indicando que no se encontró la fecha. Si el ID del subproceso de consultar resultados no es válido, se devuelve un mensaje de error.
     *
     * @param int $idAcceptanceAdmissionProcess El ID del subproceso de consultar resultados.
     *
     * @return array devuelve Un arreglo asociativo con el estado de la operación y los resultados:
     * 
     *   -Si el ID del subproceso de consultar resultados es válido y se encuentra la fecha de inicio
     *   [
     *       "status" => "success",     
     *       "startDate" => $startDate    
     *   ]
     *   
        
     *   -Si el ID del subproceso de consultar resultados es válido pero no se encuentra la fecha de inicio
     *   [
     *       "status" => "not_found",   
     *       "message" => "No se encontró la fecha de acuerdo al ID del subproceso de consultar resultados proporcionado" 
     *   ]

     *   - Si el ID del subproceso de consultar resultados no es válido
     *   [
     *       "status" => "error",    
     *       "message" => "El ID del subproceso de consultar resultados no es válido" 
     *   ]    

     *   -Si ocurre una excepción durante la ejecución de la consulta
     *   [
     *       "status" => "error",      
     *       "message" => "Excepción capturada: " . $exception->getMessage(), 
     *       "code" => $exception->getCode()  
     *   ]
     * @author Alejandro Moya 20211020462
     * @created Noviembre de 2024
     */
    public function getStartDateAcceptanceAdmissionProcess($idAcceptanceAdmissionProcess){
        try{
            if($idAcceptanceAdmissionProcess){
                $startDate = $this->connection->execute_query("CALL START_DATE_ACCEPTANCE($idAcceptanceAdmissionProcess)");
                if($startDate){
                    $rowStartDate = $startDate->fetch_assoc();
                    return [
                        "status" => "success",
                        "startDate" => $rowStartDate['start_dateof_acceptance_admission_process']
                    ];
                }else{
                    return [
                        "status" => "not_found",
                        "message" => "No se encontro la fecha de acuerdo al id del subproceso de consultar resultados proporcionado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "message" => "El ID del subproceso de consultar resultados no es valido"
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
     * Obtiene la fecha de finalizacion del subproceso de consultar resultados basado en el ID del subproceso de consultar resultados.
     *
     * Este método consulta la base de datos utilizando una procedimiento  almacenado llamado `END_DATE_ACCEPTANCE()`
     * para obtener la fecha de finalizacion de un subproceso de consultar resultados. Si el ID proporcionado es válido y la consulta se
     * realiza con éxito, se devuelve la fecha de finalizacion. Si no se encuentra la fecha, se devuelve un mensaje de error
     * indicando que no se encontró la fecha. Si el idAcceptanceAdmissionProcess no es válido, se devuelve un mensaje de error.
     *
     * @param int $idAcceptanceAdmissionProcess El ID del subproceso de consultar resultados.
     *
     * @return array devuelve Un arreglo asociativo con el estado de la operación y los resultados:
     * 
     *   -Si el ID del subproceso de consultar resultados es válido y se encuentra la fecha de finalizacion
     *   [
     *       "status" => "success",     
     *       "endDate" => $endDate    
     *   ]
     *   
        
     *   -Si el ID del subproceso de consultar resultados es válido pero no se encuentra la fecha de finalización
     *   [
     *       "status" => "not_found",   
     *       "message" => "No se encontró la fecha de acuerdo al ID del subproceso de consultar resultados" 
     *   ]

     *   - Si el ID del subproceso de consultar resultados no es válido 
     *   [
     *       "status" => "error",    
     *       "message" => "El ID del subproceso de consultar resultados no es válido" 
     *   ]    

     *   -Si ocurre una excepción durante la ejecución de la consulta
     *   [
     *       "status" => "error",     
     *       "message" => "Excepción capturada: " . $exception->getMessage(), 
     *       "code" => $exception->getCode()  
     *   ]
     * @author Alejandro Moya 20211020462
     * @created Noviembre de 2024
     */
    public function getEndDateAcceptanceAdmissionProcess($idAcceptanceAdmissionProcess){
        try{
            if($idAcceptanceAdmissionProcess){
                $endDate = $this->connection->execute_query("CALL END_DATE_ACCEPTANCE($idAcceptanceAdmissionProcess)");
                if($endDate){
                    $rowEndDate = $endDate->fetch_assoc();
                    return [
                        "status" => "success",
                        "endDate" => $rowEndDate['end_dateof_acceptance_admission_process']
                    ];
                }else{
                    return [
                        "status" => "not_found",
                        "message" => "No se encontro la fecha deacuerdo al id del subproceso de consultar resultados proporcionado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "message" => "El ID del subproceso de consultar resultados no es válido"
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
        * Verifica si el proceso de aceptacion está en curso según las fechas de inicio y fin.
        * 
        * Este método consulta el proceso de admisión activo, obtiene las fechas de inicio y fin del proceso de aceptación,
        * y verifica si la fecha actual está dentro de ese rango.
        * 
        * @author Alejandro Moya 20211020462
        * @created Noviembre de 2024
        * @return bool Retorna true si la fecha actual está dentro del rango de fechas del proceso de aceptación, false en caso contrario.
        */
    public function getVerifyAcceptanceAdmissionProcess() {
        $activeAdmissionProcess = new AdmissionProccessDAO();
        $idAdmissionProcess = $activeAdmissionProcess->getVerifyAdmissionProcess();

        $dataIdAcceptanceAdmissionProcess = $this->getIdAcceptanceAdmissionProcess( $idAdmissionProcess);
        if ( $dataIdAcceptanceAdmissionProcess['status'] != 'success') {
            echo  $dataIdAcceptanceAdmissionProcess['message'];
            return false;
        } 
        $idAcceptanceAdmissionProcess = $dataIdAcceptanceAdmissionProcess['id_acceptance_admission_process'];

        $dataStartAcceptanceAdmissionProcess = $this->getStartDateAcceptanceAdmissionProcess($idAcceptanceAdmissionProcess);
        if ( $dataStartAcceptanceAdmissionProcess['status'] != 'success') {
            echo   $dataStartAcceptanceAdmissionProcess['message'];
            return false;
        } 
        $startDateAcceptanceAdmissionProcess =  new DateTime($dataStartAcceptanceAdmissionProcess['startDate']);
     

        $dataEndDateAcceptanceAdmissionProcess = $this->getEndDateAcceptanceAdmissionProcess($idAcceptanceAdmissionProcess);
        if ($dataEndDateAcceptanceAdmissionProcess['status'] != 'success') {
            echo  $dataEndDateAcceptanceAdmissionProcess['message'];
            return false;
        } 
        $endtDateAcceptanceAdmissionProcess =   new DateTime($dataEndDateAcceptanceAdmissionProcess['endDate']);

        $currentDate = new DateTime();
        if ($currentDate >= $startDateAcceptanceAdmissionProcess && $currentDate <= $endtDateAcceptanceAdmissionProcess) {
            return true;
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
