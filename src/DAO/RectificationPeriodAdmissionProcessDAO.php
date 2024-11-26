<?php
include_once "AdmissionProccessDAO.php";

Class RectificationPeriodAdmissionProcessDAO{
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
     * Obtiene el ID del subproceso de periodo de rectificacion activo ,que pertenece al proceso de admision especificado, desde la base de datos.
     *
     * Este método ejecuta un procedimiento almacenado llamado "ACTIVE_RECTIFICATION_PERIOD()"
     * y retorna el ID del subproceso de periodo de rectificacion activo si existe.
     *
     * @param int $idAdmissionProcess El ID  del proceso de admision para el cual se desea obtener el subproceso de periodo de rectificacion activo.
     * 
     * @return array Devuelve un arreglo asociativo con el resultado de la consulta:
     *               - Si se encuentra un subproceso activo:
     *                 [
     *                   "status" => "success",
     *                   "id_rectification_period_admission_process" => int
     *                 ]
     *               - Si no se encuentra un subproceso activo:
     *                 [
     *                   "status" => "not_found",
     *                   "message" => "No se encontraron subprocesos de periodo de rectificacion activos"
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
     */
    public function getIdRectificationPeriodAdmissionProcess($idAdmissionProcess){
        try {
            $idAdmissionProcess = (int) $idAdmissionProcess; //Se asegura que se maneje como entero
            $IdsRectificationPeriodAdmissionProcess = $this->connection->execute_query("CALL ACTIVE_RECTIFICATION_PERIOD($idAdmissionProcess)");

            if ($IdsRectificationPeriodAdmissionProcess) { //Comprobar si el procedimiento almacenado se ejecuto correctamente. 
                if ($IdsRectificationPeriodAdmissionProcess->num_rows > 0) { //Comprobar si se devolvieron más de 0 identificadores de subproceso de periodo de rectificacion.
                    $IdRectificationPeriodAdmissionProcess= $IdsRectificationPeriodAdmissionProcess->fetch_assoc(); //obtener el primer identificadores de subproceso de periodo de rectificacion activo devuelto, como un arreglo asociativo.
                    return [
                        "status" => "success",
                        "id_rectification_period_admission_process" => $IdRectificationPeriodAdmissionProcess['id_rectification_period_admission_process']
                    ];
                } else {
                    return [
                        "status" => "not_found",
                        "message" => "No se encontraron subprocesos de periodo de rectificacion activos"
                    ];
                }
            } else {
                return [
                    "status" => "error",
                    "message" => "Error en el procedimiento ACTIVE_RECTIFICATION_PERIOD(): " . $this->connection->error
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
     * Obtiene la fecha de inicio del  subproceso de periodo de rectificacion basado en el ID del del subproceso de periodo de rectificacion.
     *
     * Este método consulta la base de datos utilizando una procedimiento  almacenado llamado `START_DATE_RECTIFICATION_PERIOD()`
     * para obtener la fecha de inicio del subproceso de periodo de rectificacion. Si el ID proporcionado es válido y la consulta se
     * realiza con éxito, se devuelve la fecha de inicio. Si no se encuentra la fecha, se devuelve un mensaje de error
     * indicando que no se encontró la fecha. Si el ID del subproceso de periodo de rectificacion no es válido, se devuelve un mensaje de error.
     *
     * @param int $idRectificationPeriodAdmissionProcess El ID del subproceso de periodo de rectificacion.
     *
     * @return array devuelve Un arreglo asociativo con el estado de la operación y los resultados:
     * 
     *   -Si el ID del subproceso de periodo de rectificacion es válido y se encuentra la fecha de inicio
     *   [
     *       "status" => "success",     
     *       "startDate" => $startDate    
     *   ]
     *   
        
     *   -Si el ID del subproceso de periodo de rectificacion es válido pero no se encuentra la fecha de inicio
     *   [
     *       "status" => "not_found",   
     *       "message" => "No se encontró la fecha de acuerdo al ID del subproceso de periodo de rectificacion proporcionado" 
     *   ]

     *   - Si el ID del subproceso de periodo de rectificacion no es válido
     *   [
     *       "status" => "error",    
     *       "message" => "El ID del subproceso de periodo de rectificacion no es válido" 
     *   ]    

     *   -Si ocurre una excepción durante la ejecución de la consulta
     *   [
     *       "status" => "error",      
     *       "message" => "Excepción capturada: " . $exception->getMessage(), 
     *       "code" => $exception->getCode()  
     *   ]
     */
    public function getStartDateRectificationPeriodAdmissionProcess($idRectificationPeriodAdmissionProcess){
        try{
            if($idRectificationPeriodAdmissionProcess){
                $startDates = $this->connection->execute_query("CALL START_DATE_RECTIFICATION_PERIOD($idRectificationPeriodAdmissionProcess)");
                if($startDates){
                    $startDate = $startDates->fetch_assoc();
                    return [
                        "status" => "success",
                        "startDate" => $startDate['start_dateof_rectification_period_admission_process']
                    ];
                }else{
                    return [
                        "status" => "not_found",
                        "message" => "No se encontro la fecha de acuerdo al id del subproceso de periodo de rectificacion proporcionado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "message" => "El ID del subproceso de periodo de rectificacion no es valido"
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
     * Obtiene la fecha de finalizacion del subproceso de periodo de rectificacion basado en el ID del subproceso de periodo de rectificacion.
     *
     * Este método consulta la base de datos utilizando una procedimiento  almacenado llamado `END_DATE_INSCRIPTION_PROCESS()`
     * para obtener la fecha de finalizacion de un subproceso de periodo de rectificacion. Si el ID proporcionado es válido y la consulta se
     * realiza con éxito, se devuelve la fecha de finalizacion. Si no se encuentra la fecha, se devuelve un mensaje de error
     * indicando que no se encontró la fecha. Si el idRectificationPeriodAdmissionProcess no es válido, se devuelve un mensaje de error.
     *
     * @param int $idRectificationPeriodAdmissionProcess El ID del subproceso de periodo de rectificacion.
     *
     * @return array devuelve Un arreglo asociativo con el estado de la operación y los resultados:
     * 
     *   -Si el ID del subproceso de periodo de rectificacion es válido y se encuentra la fecha de finalizacion
     *   ["status" => "success", "endDate" => $endDate ]
     *   
     *   -Si el ID del subproceso de periodo de rectificacion es válido pero no se encuentra la fecha de finalización
     *   [ "status" => "not_found",  "message" => "No se encontró la fecha de acuerdo al ID del subproceso de periodo de rectificacion"]
     *
     *   - Si el ID del subproceso de periodo de rectificacion no es válido 
     *   ["status" => "error",  "message" => "El ID del subproceso de periodo de rectificacion no es válido" ]    
     *
     *   -Si ocurre una excepción durante la ejecución de la consulta
     *   ["status" => "error",  "message" => "Excepción capturada: " . $exception->getMessage(), "code" => $exception->getCode()]
     */
    public function getEndDateRectificationPeriodAdmissionProcess($idRectificationPeriodAdmissionProcess){
        try{
            if($idRectificationPeriodAdmissionProcess){
                $endDates = $this->connection->execute_query("CALL END_DATE_RECTIFICATION_PERIOD($idRectificationPeriodAdmissionProcess)");
                if($endDates){
                    $endDate = $endDates->fetch_assoc();
                    return [
                        "status" => "success",
                        "endDate" => $endDate['end_dateof_rectification_period_admission_process']
                    ];
                }else{
                    return [
                        "status" => "not_found",
                        "message" => "No se encontro la fecha deacuerdo al id del subproceso de periodo de rectificacion proporcionado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "message" => "El ID del subproceso de periodo de rectificacion no es válido"
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
    
    public function getVerifyRectificationPeriodAdmissionProcess(){
        $activeAdmissionProcess = new AdmissionProccessDAO();
        $idAdmissionProcess = $activeAdmissionProcess->getVerifyAdmissionProcess();

        $dataIdRectificationPeriodAdmissionProcess = $this->getIdRectificationPeriodAdmissionProcess( $idAdmissionProcess);
        if ( $dataIdRectificationPeriodAdmissionProcess['status'] != 'success') {
            echo  $dataIdRectificationPeriodAdmissionProcess['message'];
            return false;
        } 
        $idRectificationPeriodAdmissionProcess = $dataIdRectificationPeriodAdmissionProcess['id_rectification_period_admission_process'];

        $dataStartRectificationPeriodAdmissionProcess = $this->getStartDateRectificationPeriodAdmissionProcess($idRectificationPeriodAdmissionProcess);
        if ( $dataStartRectificationPeriodAdmissionProcess['status'] != 'success') {
            echo   $dataStartRectificationPeriodAdmissionProcess['message'];
            return false;
        } 
        $startDateRectificationPeriodAdmissionProcess =  new DateTime($dataStartRectificationPeriodAdmissionProcess['startDate']);
     

        $dataEndDateRectificationPeriodAdmissionProcess = $this->getEndDateRectificationPeriodAdmissionProcess($idRectificationPeriodAdmissionProcess);
        if ($dataEndDateRectificationPeriodAdmissionProcess['status'] != 'success') {
            echo  $dataEndDateRectificationPeriodAdmissionProcess['message'];
            return false;
        } 
        $endtDateRectificationPeriodAdmissionProcess =   new DateTime($dataEndDateRectificationPeriodAdmissionProcess['endDate']);

        $currentDate = new DateTime();
        if ($currentDate >= $startDateRectificationPeriodAdmissionProcess && $currentDate <= $endtDateRectificationPeriodAdmissionProcess) {
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