<?php
include_once "AdmissionProccessDAO.php";

Class SendingNotificationsAdmissionProcessDAO{
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
     * Obtiene el ID del subproceso de envio de notificaciones activo ,que pertenece al proceso de admision especificado, desde la base de datos.
     *
     * Este método ejecuta un procedimiento almacenado llamado "ACTIVE_SENDING_NOTIFICATIONS()"
     * y retorna el ID del subproceso de envio de notificaciones activo si existe.
     *
     * @param int $idAdmissionProcess El ID  del proceso de admision para el cual se desea obtener el subproceso de envio de notificaciones activo.
     * 
     * @return array Devuelve un arreglo asociativo con el resultado de la consulta:
     *               - Si se encuentra un proceso activo:
     *                 [
     *                   "status" => "success",
     *                   "id_sending_notifications_admission_process" => int
     *                 ]
     *               - Si no se encuentra un proceso activo:
     *                 [
     *                   "status" => "not_found",
     *                   "message" => "No se encontraron subprocesos de inscripción activos"
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
    public function getIdSendingNotificationsAdmissionProcess($idAdmissionProcess){
        try {
            $idAdmissionProcess = (int) $idAdmissionProcess; //Se asegura que se maneje como entero
            $IdsSendingNotificationsAdmissionProcess = $this->connection->execute_query("CALL ACTIVE_SENDING_NOTIFICATIONS($idAdmissionProcess)");

            if ($IdsSendingNotificationsAdmissionProcess) { //Comprobar si el procedimiento almacenado se ejecuto correctamente. 
                if ($IdsSendingNotificationsAdmissionProcess->num_rows > 0) { //Comprobar si se devolvieron más de 0 identificadores del subproceso de envio de notifications.
                    $IdSendingNotificationsAdmissionProcess= $IdsSendingNotificationsAdmissionProcess->fetch_assoc(); //obtener el primer identificadores del subproceso de envio de notifications activo devuelto, como un arreglo asociativo.
                    return [
                        "status" => "success",
                        "id_sending_notifications_admission_process" => $IdSendingNotificationsAdmissionProcess['id_sending_notifications_admission_process']
                    ];
                } else {
                    return [
                        "status" => "not_found",
                        "message" => "No se encontraron subprocesos de envio de notifications activos"
                    ];
                }
            } else {
                return [
                    "status" => "error",
                    "message" => "Error en el procedimiento ACTIVE_SENDING_NOTIFICATIONS(): " . $this->connection->error
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
     * Obtiene la fecha de inicio del  subproceso de envio de notificaciones basado en el ID del del subproceso de envio de notificaciones.
     *
     * Este método consulta la base de datos utilizando una procedimiento  almacenado llamado `START_DATE_SENDING_NOTIFICATIONS()`
     * para obtener la fecha de inicio del subproceso de envio de notificaciones. Si el ID proporcionado es válido y la consulta se
     * realiza con éxito, se devuelve la fecha de inicio. Si no se encuentra la fecha, se devuelve un mensaje de error
     * indicando que no se encontró la fecha. Si el ID del subproceso de envio de notificaciones no es válido, se devuelve un mensaje de error.
     *
     * @param int $idSendingNotificationsAdmissionProcess El ID del subproceso de envio de notificaciones.
     *
     * @return array devuelve Un arreglo asociativo con el estado de la operación y los resultados:
     * 
     *   -Si el ID del subproceso de envio de notificaciones es válido y se encuentra la fecha de inicio
     *   [
     *       "status" => "success",     
     *       "startDate" => $startDate    
     *   ]
     *   
        
     *   -Si el ID del subproceso de envio de notificaciones es válido pero no se encuentra la fecha de inicio
     *   [
     *       "status" => "not_found",   
     *       "message" => "No se encontró la fecha de acuerdo al ID del subproceso de envio de notificaciones proporcionado" 
     *   ]

     *   - Si el ID del subproceso de envio de notificaciones no es válido
     *   [
     *       "status" => "error",    
     *       "message" => "El ID del subproceso de envio de notificaciones no es válido" 
     *   ]    

     *   -Si ocurre una excepción durante la ejecución de la consulta
     *   [
     *       "status" => "error",      
     *       "message" => "Excepción capturada: " . $exception->getMessage(), 
     *       "code" => $exception->getCode()  
     *   ]
     */
    public function getStartDateSendingNotificationsAdmissionProcess($idSendingNotificationsAdmissionProcess){
        try{
            if($idSendingNotificationsAdmissionProcess){
                $startDates = $this->connection->execute_query("CALL START_DATE_SENDING_NOTIFICATIONS($idSendingNotificationsAdmissionProcess)");
                if($startDates){
                    $startDate = $startDates->fetch_assoc();
                    return [
                        "status" => "success",
                        "startDate" => $startDate['start_dateof_sending_notifications_admission_process']
                    ];
                }else{
                    return [
                        "status" => "not_found",
                        "message" => "No se encontro la fecha de acuerdo al id del subproceso de envio de notificaciones proporcionado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "message" => "El ID del subproceso de envio de notificaciones no es valido"
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
     * Obtiene la fecha de finalizacion del subproceso de envio de notificaciones basado en el ID del subproceso de envio de notificaciones.
     *
     * Este método consulta la base de datos utilizando una procedimiento  almacenado llamado `END_DATE_SENDING_NOTIFICATIONS()`
     * para obtener la fecha de finalizacion de un subproceso de envio de notificaciones. Si el ID proporcionado es válido y la consulta se
     * realiza con éxito, se devuelve la fecha de finalizacion. Si no se encuentra la fecha, se devuelve un mensaje de error
     * indicando que no se encontró la fecha. Si el idSendingNotificationsAdmissionProcess no es válido, se devuelve un mensaje de error.
     *
     * @param int $idSendingNotificationsAdmissionProcess El ID del subproceso de envio de notificaciones.
     *
     * @return array devuelve Un arreglo asociativo con el estado de la operación y los resultados:
     * 
     *   -Si el ID del subproceso de envio de notificaciones es válido y se encuentra la fecha de finalizacion
     *   [
     *       "status" => "success",     
     *       "endDate" => $endDate    
     *   ]
     *   
        
     *   -Si el ID del subproceso de envio de notificaciones es válido pero no se encuentra la fecha de finalización
     *   [
     *       "status" => "not_found",   
     *       "message" => "No se encontró la fecha de acuerdo al ID del subproceso de envio de notificaciones" 
     *   ]

     *   - Si el ID del subproceso de envio de notificaciones no es válido 
     *   [
     *       "status" => "error",    
     *       "message" => "El ID del subproceso de envio de notificaciones no es válido" 
     *   ]    

     *   -Si ocurre una excepción durante la ejecución de la consulta
     *   [
     *       "status" => "error",     
     *       "message" => "Excepción capturada: " . $exception->getMessage(), 
     *       "code" => $exception->getCode()  
     *   ]
     */
    public function getEndDateSendingNotificationsAdmissionProcess($idSendingNotificationsAdmissionProcess){
        try{
            if($idSendingNotificationsAdmissionProcess){
                $endDates = $this->connection->execute_query("CALL END_DATE_SENDING_NOTIFICATIONS($idSendingNotificationsAdmissionProcess)");
                if($endDates){
                    $endDate = $endDates->fetch_assoc();
                    return [
                        "status" => "success",
                        "endDate" => $endDate['end_dateof_sending_notifications_admission_process']
                    ];
                }else{
                    return [
                        "status" => "not_found",
                        "message" => "No se encontro la fecha   al id del subproceso de envio de notificaciones proporcionado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "message" => "El ID del subproceso de envio de notificaciones no es válido"
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
     * Obtiene la hora de inicio del  subproceso de envio de notificaciones basado en el ID del del subproceso de envio de notificaciones.
     *
     * Este método consulta la base de datos utilizando una procedimiento  almacenado llamado `START_TIME_SENDING_NOTIFICATIONS()`
     * para obtener la hora de inicio del subproceso de envio de notificaciones. Si el ID proporcionado es válido y la consulta se
     * realiza con éxito, se devuelve la hora de inicio. Si no se encuentra la hora, se devuelve un mensaje de error
     * indicando que no se encontró la hora. Si el ID del subproceso de envio de notificaciones no es válido, se devuelve un mensaje de error. 
     *
     * @param int $idSendingNotificationsAdmissionProcess El ID del subproceso de envio de notificaciones.
     *
     * @return array devuelve Un arreglo asociativo con el estado de la operación y los resultados:
     * 
     *   -Si el ID del subproceso de envio de notificaciones es válido y se encuentra la hora de inicio
     *   [
     *       "status" => "success",     
     *       "startTime" => $startTime   
     *   ]
     *   
        
     *   -Si el ID del subproceso de envio de notificaciones es válido pero no se encuentra la hora de inicio
     *   [
     *       "status" => "not_found",   
     *       "message" => "No se encontró la hora de acuerdo al ID del subproceso de envio de notificaciones proporcionado" 
     *   ]

     *   - Si el ID del subproceso de envio de notificaciones no es válido
     *   [
     *       "status" => "error",    
     *       "message" => "El ID del subproceso de envio de notificaciones no es válido" 
     *   ]    

     *   -Si ocurre una excepción durante la ejecución de la consulta
     *   [
     *       "status" => "error",      
     *       "message" => "Excepción capturada: " . $exception->getMessage(), 
     *       "code" => $exception->getCode()  
     *   ]
     */
    public function getStartTimeSendingNotificationsAdmissionProcess($idSendingNotificationsAdmissionProcess){
        try{
            if($idSendingNotificationsAdmissionProcess){
                $startTimes = $this->connection->execute_query("CALL START_TIME_SENDING_NOTIFICATIONS($idSendingNotificationsAdmissionProcess)");
                if($startTimes){
                    $startTime = $startTimes->fetch_assoc();
                    return [
                        "status" => "success",
                        "startTime" => $startTime['star_timeof_sending_notifications_admission_process']
                    ];
                }else{
                    return [
                        "status" => "not_found",
                        "message" => "No se encontro la hora de acuerdo al id del subproceso de envio de notificaciones proporcionado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "message" => "El ID del subproceso de envio de notificaciones no es valido"
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
     * Obtiene la hora de finalizacion del subproceso de envio de notificaciones basado en el ID del subproceso de envio de notificaciones.
     *
     * Este método consulta la base de datos utilizando una procedimiento  almacenado llamado `END_TIME_SENDING_NOTIFICATIONS()`
     * para obtener la hora de finalizacion de un subproceso de envio de notificaciones. Si el ID proporcionado es válido y la consulta se
     * realiza con éxito, se devuelve la hora de finalizacion. Si no se encuentra la hora, se devuelve un mensaje de error
     * indicando que no se encontró la hora. Si el idSendingNotificationsAdmissionProcess no es válido, se devuelve un mensaje de error.
     *
     * @param int $idSendingNotificationsAdmissionProcess El ID del subproceso de envio de notificaciones.
     *
     * @return array devuelve Un arreglo asociativo con el estado de la operación y los resultados:
     * 
     *   -Si el ID del subproceso de envio de notificaciones es válido y se encuentra la hora de finalizacion
     *   [
     *       "status" => "success",     
     *       "endTime" => $endTime    
     *   ]
     *   
        
     *   -Si el ID del subproceso de envio de notificaciones es válido pero no se encuentra la hora de finalización
     *   [
     *       "status" => "not_found",   
     *       "message" => "No se encontró la hora de acuerdo al ID del subproceso de envio de notificaciones" 
     *   ]

     *   - Si el ID del subproceso de envio de notificaciones no es válido 
     *   [
     *       "status" => "error",    
     *       "message" => "El ID del subproceso de envio de notificaciones no es válido" 
     *   ]    

     *   -Si ocurre una excepción durante la ejecución de la consulta
     *   [
     *       "status" => "error",     
     *       "message" => "Excepción capturada: " . $exception->getMessage(), 
     *       "code" => $exception->getCode()  
     *   ]
     */
    public function getEndTimeSendingNotificationsAdmissionProcess($idSendingNotificationsAdmissionProcess){
        try{
            if($idSendingNotificationsAdmissionProcess){
                $endTimes = $this->connection->execute_query("CALL END_TIME_SENDING_NOTIFICATIONS($idSendingNotificationsAdmissionProcess)");
                if($endTimes){
                    $endTime = $endTimes->fetch_assoc();
                    return [
                        "status" => "success",
                        "endTime" => $endTime['end_timeof_sending_notifications_admission_process']
                    ];
                }else{
                    return [
                        "status" => "not_found",
                        "message" => "No se encontro la hora de acuerdo al id del subproceso de envio de notificaciones proporcionado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "message" => "El ID del subproceso de envio de notificaciones no es válido"
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

    public function getVerifySendingNotificationsAdmissionProcess(){
        $activeAdmissionProcess = new AdmissionProccessDAO();
        $idAdmissionProcess = $activeAdmissionProcess->getVerifyAdmissionProcess();

        $dataIdSendingNotificationsAdmissionProcess = $this->getIdSendingNotificationsAdmissionProcess($idAdmissionProcess);
        if ( $dataIdSendingNotificationsAdmissionProcess['status'] != 'success') {
            echo  $dataIdSendingNotificationsAdmissionProcess['message'];
            return false;
        } 
        $idSendingNotificationsAdmissionProcess = $dataIdSendingNotificationsAdmissionProcess['id_sending_notifications_admission_process'];

        $dataStartSendingNotificationsAdmissionProcess = $this->getStartDateSendingNotificationsAdmissionProcess($idSendingNotificationsAdmissionProcess);
        if ( $dataStartSendingNotificationsAdmissionProcess['status'] != 'success') {
            echo   $dataStartSendingNotificationsAdmissionProcess['message'];
            return false;
        } 
        $startDateSendingNotificationsAdmissionProcess =  new DateTime($dataStartSendingNotificationsAdmissionProcess['startDate']);
     

        $dataEndDateSendingNotificationsAdmissionProcess = $this->getEndDateSendingNotificationsAdmissionProcess($idSendingNotificationsAdmissionProcess);
        if ($dataEndDateSendingNotificationsAdmissionProcess['status'] != 'success') {
            echo  $dataEndDateSendingNotificationsAdmissionProcess['message'];
            return false;
        } 
        $endtDateSendingNotificationsAdmissionProcess =   new DateTime($dataEndDateSendingNotificationsAdmissionProcess['endDate']);

        $currentDate = new DateTime();
        if ($currentDate >= $startDateSendingNotificationsAdmissionProcess && $currentDate <= $endtDateSendingNotificationsAdmissionProcess) {
            return true;
        } else {
            return false;
        }
    }

    public function getVerifyTimeSendingNotificationsAdmissionProcess(){
        $activeAdmissionProcess = new AdmissionProccessDAO();
        $idAdmissionProcess = $activeAdmissionProcess->getVerifyAdmissionProcess();

        $dataIdSendingNotificationsAdmissionProcess = $this->getIdSendingNotificationsAdmissionProcess($idAdmissionProcess);
        if ( $dataIdSendingNotificationsAdmissionProcess['status'] != 'success') {
            echo  $dataIdSendingNotificationsAdmissionProcess['message'];
            return false;
        } 
        $idSendingNotificationsAdmissionProcess = $dataIdSendingNotificationsAdmissionProcess['id_sending_notifications_admission_process'];

        $dataStartSendingNotificationsAdmissionProcess = $this->getStartTimeSendingNotificationsAdmissionProcess($idSendingNotificationsAdmissionProcess);
        if ( $dataStartSendingNotificationsAdmissionProcess['status'] != 'success') {
            echo   $dataStartSendingNotificationsAdmissionProcess['message'];
            return false;
        } 
        $startTimeSendingNotificationsAdmissionProcess =  new DateTime($dataStartSendingNotificationsAdmissionProcess['startTime']);
     

        $dataEndTimeSendingNotificationsAdmissionProcess = $this->getEndTimeSendingNotificationsAdmissionProcess($idSendingNotificationsAdmissionProcess);
        if ($dataEndTimeSendingNotificationsAdmissionProcess['status'] != 'success') {
            echo  $dataEndTimeSendingNotificationsAdmissionProcess['message'];
            return false;
        } 
        $endtTimeSendingNotificationsAdmissionProcess =   new DateTime($dataEndTimeSendingNotificationsAdmissionProcess['endTime']);

      
        $currentTime = new DateTime();
        if ($currentTime >= $startTimeSendingNotificationsAdmissionProcess && $currentTime <= $endtTimeSendingNotificationsAdmissionProcess) {
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