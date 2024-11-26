<?php
include_once "AdmissionProccessDAO.php";

Class RegistrationRatingAdmissionProcessDAO{
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
     * Obtiene el ID del subproceso de registro de calificaciones activo ,que pertenece al proceso de admision especificado, desde la base de datos.
     *
     * Este método ejecuta un procedimiento almacenado llamado "ACTIVE_REGISTRATION_RATING()"
     * y retorna el ID del subproceso de registro de calificaciones activo, si existe.
     *
     * @param int $idAdmissionProcess El ID  del proceso de admision para el cual se desea obtener el subproceso de registro de calificaciones activo.
     * 
     * @return array Devuelve un arreglo asociativo con el resultado de la consulta:
     *               - Si se encuentra un proceso activo:
     *                 [
     *                   "status" => "success",
     *                   "id_registration_rating_admission_process" => int
     *                 ]
     *               - Si no se encuentra un proceso activo:
     *                 [
     *                   "status" => "not_found",
     *                   "message" => "No se encontraron procesos de inscripción activos"
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
    public function getIdRegistrationRatingAdmissionProcess($idAdmissionProcess){
        try {
            $idAdmissionProcess = (int) $idAdmissionProcess; //Se asegura que se maneje como entero
            $IdsRegistrationRatingAdmissionProcess = $this->connection->execute_query("CALL ACTIVE_REGISTRATION_RATING($idAdmissionProcess)");

            if ($IdsRegistrationRatingAdmissionProcess) { //Comprobar si el procedimiento almacenado se ejecuto correctamente. 
                if ($IdsRegistrationRatingAdmissionProcess->num_rows > 0) { //Comprobar si se devolvieron más de 0 identificadores de proceso de registro de calificaciones.
                    $IdRegistrationRatingAdmissionProcess= $IdsRegistrationRatingAdmissionProcess->fetch_assoc(); //obtener el primer identificadores de proceso de registro de calificaciones activo devuelto, como un arreglo asociativo.
                    return [
                        "status" => "success",
                        "id_registration_rating_admission_process" => $IdRegistrationRatingAdmissionProcess['id_registration_rating_admission_process']
                    ];
                } else {
                    return [
                        "status" => "not_found",
                        "message" => "No se encontraron subprocesos de registro de calificaciones activos"
                    ];
                }
            } else {
                return [
                    "status" => "error",
                    "message" => "Error en el procedimiento ACTIVE_REGISTRATION_RATING(): " . $this->connection->error
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
     * Obtiene la fecha de inicio del  subproceso de registro de calificaciones basado en el ID del del subproceso de registro de calificaciones.
     *
     * Este método consulta la base de datos utilizando una procedimiento  almacenado llamado `START_DATE_REGISTRATION_RATING()`
     * para obtener la fecha de inicio del subproceso de registro de calificaciones. Si el ID proporcionado es válido y la consulta se
     * realiza con éxito, se devuelve la fecha de inicio. Si no se encuentra la fecha, se devuelve un mensaje de error
     * indicando que no se encontró la fecha. Si el ID del subproceso de registro de calificaciones no es válido, se devuelve un mensaje de error.
     *
     * @param int $idRegistrationRatingAdmissionProcess El ID del subproceso de registro de calificaciones.
     *
     * @return array devuelve Un arreglo asociativo con el estado de la operación y los resultados:
     * 
     *   -Si el ID del subproceso de registro de calificaciones es válido y se encuentra la fecha de inicio
     *   [
     *       "status" => "success",     
     *       "starDate" => $startDate    
     *   ]
     *   
        
     *   -Si el ID del subproceso de registro de calificaciones es válido pero no se encuentra la fecha de inicio
     *   [
     *       "status" => "not_found",   
     *       "message" => "No se encontró la fecha de acuerdo al ID del subproceso de registro de calificaciones proporcionado" 
     *   ]

     *   - Si el ID del subproceso de registro de calificaciones no es válido
     *   [
     *       "status" => "error",    
     *       "message" => "El ID del subproceso de registro de calificaciones no es válido" 
     *   ]    

     *   -Si ocurre una excepción durante la ejecución de la consulta
     *   [
     *       "status" => "error",      
     *       "message" => "Excepción capturada: " . $exception->getMessage(), 
     *       "code" => $exception->getCode()  
     *   ]
     */
    public function getStartDateRegistrationRatingAdmissionProcess($idRegistrationRatingAdmissionProcess){
        try{
            if($idRegistrationRatingAdmissionProcess){
                $startDates = $this->connection->execute_query("CALL START_DATE_REGISTRATION_RATING($idRegistrationRatingAdmissionProcess)");
                if($startDates){
                    $startDate = $startDates->fetch_assoc();
                    return [
                        "status" => "success",
                        "startDate" => $startDate['start_dateof_registration_rating_admission_process']
                    ];
                }else{
                    return [
                        "status" => "not_found",
                        "message" => "No se encontro la fecha de acuerdo al id del subproceso de registro de calificaciones proporcionado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "message" => "El ID del subproceso de registro de calificaciones no es valido"
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
     * Obtiene la fecha de finalizacion del subproceso de registro de calificaciones basado en el ID del subproceso de registro de calificaciones.
     *
     * Este método consulta la base de datos utilizando una procedimiento  almacenado llamado `END_DATE_REGISTRATION_RATING()`
     * para obtener la fecha de finalizacion de un subproceso de registro de calificaciones. Si el ID proporcionado es válido y la consulta se
     * realiza con éxito, se devuelve la fecha de finalizacion. Si no se encuentra la fecha, se devuelve un mensaje de error
     * indicando que no se encontró la fecha. Si el idRegistrationRatingAdmissionProcess no es válido, se devuelve un mensaje de error.
     *
     * @param int $idRegistrationRatingAdmissionProcess El ID del subproceso de registro de calificaciones.
     *
     * @return array devuelve Un arreglo asociativo con el estado de la operación y los resultados:
     * 
     *   -Si el ID del subproceso de registro de calificaciones es válido y se encuentra la fecha de finalizacion
     *   [
     *       "status" => "success",     
     *       "endDate" => $endDate    
     *   ]
     *   
        
     *   -Si el ID del subproceso de registro de calificaciones es válido pero no se encuentra la fecha de finalización
     *   [
     *       "status" => "not_found",   
     *       "message" => "No se encontró la fecha de acuerdo al ID del subproceso de registro de calificaciones" 
     *   ]

     *   - Si el ID del subproceso de registro de calificaciones no es válido 
     *   [
     *       "status" => "error",    
     *       "message" => "El ID del subproceso de registro de calificaciones no es válido" 
     *   ]    

     *   -Si ocurre una excepción durante la ejecución de la consulta
     *   [
     *       "status" => "error",     
     *       "message" => "Excepción capturada: " . $exception->getMessage(), 
     *       "code" => $exception->getCode()  
     *   ]
     */
    public function getEndDateRegistrationRatingAdmissionProcess($idRegistrationRatingAdmissionProcess){
        try{
            if($idRegistrationRatingAdmissionProcess){
                $endDates = $this->connection->execute_query("CALL END_DATE_REGISTRATION_RATING($idRegistrationRatingAdmissionProcess)");
                if($endDates){
                    $endDate = $endDates->fetch_assoc();
                    return [
                        "status" => "success",
                        "endDate" => $endDate['end_dateof_registration_rating_admission_process']
                    ];
                }else{
                    return [
                        "status" => "not_found",
                        "message" => "No se encontro la fecha deacuerdo al id del subproceso de registro de calificaciones proporcionado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "message" => "El ID del subproceso de registro de calificaciones no es válido"
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

    public function getVerifyRegistrationRatingAdmissionProcess(){
        $activeAdmissionProcess = new AdmissionProccessDAO();
        $idAdmissionProcess = $activeAdmissionProcess->getVerifyAdmissionProcess();

        $dataIdRegistrationRatingAdmissionProcess = $this->getIdRegistrationRatingAdmissionProcess($idAdmissionProcess);
        if ( $dataIdRegistrationRatingAdmissionProcess['status'] != 'success') {
            echo  $dataIdRegistrationRatingAdmissionProcess['message'];
            return false;
        } 
        $idRegistrationRatingAdmissionProcess = $dataIdRegistrationRatingAdmissionProcess['id_registration_rating_admission_process'];

        $dataStartRegistrationRatingAdmissionProcess = $this->getStartDateRegistrationRatingAdmissionProcess($idRegistrationRatingAdmissionProcess);
        if ( $dataStartRegistrationRatingAdmissionProcess['status'] != 'success') {
            echo   $dataStartRegistrationRatingAdmissionProcess['message'];
            return false;
        } 
        $startDateRegistrationRatingAdmissionProcess =  new DateTime($dataStartRegistrationRatingAdmissionProcess['startDate']);
     

        $dataEndDateRegistrationRatingAdmissionProcess = $this->getEndDateRegistrationRatingAdmissionProcess($idRegistrationRatingAdmissionProcess);
        if ($dataEndDateRegistrationRatingAdmissionProcess['status'] != 'success') {
            echo  $dataEndDateRegistrationRatingAdmissionProcess['message'];
            return false;
        } 
        $endtDateRegistrationRatingAdmissionProcess =   new DateTime($dataEndDateRegistrationRatingAdmissionProcess['endDate']);

        $currentDate = new DateTime();
        if ($currentDate >= $startDateRegistrationRatingAdmissionProcess && $currentDate <= $endtDateRegistrationRatingAdmissionProcess) {
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