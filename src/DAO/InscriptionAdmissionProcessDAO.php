<?php
include_once "AdmissionProccessDAO.php";

Class InscriptionAdmissionProcessDAO{
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
     * Obtiene el ID del proceso de inscripción activo ,que pertenece al proceso de admision especificado, desde la base de datos.
     *
     * Este método ejecuta un procedimiento almacenado llamado "ACTIVE_INSCRIPTION_PROCESS()"
     * y retorna el ID del proceso de inscripción activo si existe.
     *
     * @param int $idAdmissionProcess El ID  del proceso de admision para el cual se desea obtener el proceso de inscripción activo.
     * 
     * @return array Devuelve un arreglo asociativo con el resultado de la consulta:
     *               - Si se encuentra un proceso activo:
     *                 [
     *                   "status" => "success",
     *                   "id_inscription_admission_process" => int
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
    public function getIdInscriptionAdmissionProcess($idAdmissionProcess){
        try {
            $idAdmissionProcess = (int) $idAdmissionProcess; //Se asegura que se maneje como entero
            $IdsInscriptionAdmissionProcess = $this->connection->execute_query("CALL ACTIVE_INSCRIPTION_PROCESS($idAdmissionProcess)");

            if ($IdsInscriptionAdmissionProcess) { //Comprobar si el procedimiento almacenado se ejecuto correctamente. 
                if ($IdsInscriptionAdmissionProcess->num_rows > 0) { //Comprobar si se devolvieron más de 0 identificadores de proceso de inscripcion.
                    $IdInscriptionAdmissionProcess= $IdsInscriptionAdmissionProcess->fetch_assoc(); //obtener el primer identificadores de proceso de inscripcion activo devuelto, como un arreglo asociativo.
                    return [
                        "status" => "success",
                        "id_inscription_admission_process" => $IdInscriptionAdmissionProcess['id_inscription_admission_process']
                    ];
                } else {
                    return [
                        "status" => "not_found",
                        "message" => "No se encontraron procesos de inscripcion activos"
                    ];
                }
            } else {
                return [
                    "status" => "error",
                    "message" => "Error en el procedimiento ACTIVE_INSCRIPTION_PROCESS(): " . $this->connection->error
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
     * Obtiene la fecha de inicio del  subproceso de inscripcion basado en el ID del del subproceso de inscripcion.
     *
     * Este método consulta la base de datos utilizando una procedimiento  almacenado llamado `START_DATE_INSCRIPTION_PROCESS()`
     * para obtener la fecha de inicio del subproceso de inscripcion. Si el ID proporcionado es válido y la consulta se
     * realiza con éxito, se devuelve la fecha de inicio. Si no se encuentra la fecha, se devuelve un mensaje de error
     * indicando que no se encontró la fecha. Si el ID del subproceso de inscripcion no es válido, se devuelve un mensaje de error.
     *
     * @param int $idInscriptionAdmissionProcess El ID del subproceso de inscripcion.
     *
     * @return array devuelve Un arreglo asociativo con el estado de la operación y los resultados:
     * 
     *   -Si el ID del subproceso de inscripcion es válido y se encuentra la fecha de inicio
     *   [
     *       "status" => "success",     
     *       "startDate" => $startDate    
     *   ]
     *   
        
     *   -Si el ID del subproceso de inscripcion es válido pero no se encuentra la fecha de inicio
     *   [
     *       "status" => "not_found",   
     *       "message" => "No se encontró la fecha de acuerdo al ID del subproceso de inscripcion proporcionado" 
     *   ]

     *   - Si el ID del subproceso de inscripcion no es válido
     *   [
     *       "status" => "error",    
     *       "message" => "El ID del subproceso de inscripcion no es válido" 
     *   ]    

     *   -Si ocurre una excepción durante la ejecución de la consulta
     *   [
     *       "status" => "error",      
     *       "message" => "Excepción capturada: " . $exception->getMessage(), 
     *       "code" => $exception->getCode()  
     *   ]
     */
    public function getStartDateInscriptionAdmissionProcess($idInscriptionAdmissionProcess){
        try{
            if($idInscriptionAdmissionProcess){
                $startDates = $this->connection->execute_query("CALL START_DATE_INSCRIPTION_PROCESS($idInscriptionAdmissionProcess)");
                if($startDates){
                    $startDate = $startDates->fetch_assoc();
                    return [
                        "status" => "success",
                        "startDate" => $startDate['start_dateof_inscription_admission_process']
                    ];
                }else{
                    return [
                        "status" => "not_found",
                        "message" => "No se encontro la fecha de acuerdo al id del subproceso de inscripcion proporcionado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "message" => "El ID del subproceso de inscripcion no es valido"
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
     * Obtiene la fecha de finalizacion del subproceso de inscripcion basado en el ID del subproceso de inscripcion.
     *
     * Este método consulta la base de datos utilizando una procedimiento  almacenado llamado `END_DATE_INSCRIPTION_PROCESS()`
     * para obtener la fecha de finalizacion de un subproceso de inscripcion. Si el ID proporcionado es válido y la consulta se
     * realiza con éxito, se devuelve la fecha de finalizacion. Si no se encuentra la fecha, se devuelve un mensaje de error
     * indicando que no se encontró la fecha. Si el idInscriptionAdmissionProcess no es válido, se devuelve un mensaje de error.
     *
     * @param int $idInscriptionAdmissionProcess El ID del subproceso de inscripcion.
     *
     * @return array devuelve Un arreglo asociativo con el estado de la operación y los resultados:
     * 
     *   -Si el ID del subproceso de inscripcion es válido y se encuentra la fecha de finalizacion
     *   [
     *       "status" => "success",     
     *       "endDate" => $endDate    
     *   ]
     *   
        
     *   -Si el ID del subproceso de inscripcion es válido pero no se encuentra la fecha de finalización
     *   [
     *       "status" => "not_found",   
     *       "message" => "No se encontró la fecha de acuerdo al ID del subproceso de inscripcion" 
     *   ]

     *   - Si el ID del subproceso de inscripcion no es válido 
     *   [
     *       "status" => "error",    
     *       "message" => "El ID del subproceso de inscripcion no es válido" 
     *   ]    

     *   -Si ocurre una excepción durante la ejecución de la consulta
     *   [
     *       "status" => "error",     
     *       "message" => "Excepción capturada: " . $exception->getMessage(), 
     *       "code" => $exception->getCode()  
     *   ]
     */
    public function getEndDateInscriptionAdmissionProcess($idInscriptionAdmissionProcess){
        try{
            if($idInscriptionAdmissionProcess){
                $endDates = $this->connection->execute_query("CALL END_DATE_INSCRIPTION_PROCESS($idInscriptionAdmissionProcess)");
                if($endDates){
                    $endDate = $endDates->fetch_assoc();
                    return [
                        "status" => "success",
                        "endDate" => $endDate['end_dateof_inscription_admission_process']
                    ];
                }else{
                    return [
                        "status" => "not_found",
                        "message" => "No se encontro la fecha deacuerdo al id del subproceso de inscripcion proporcionado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "message" => "El ID del subproceso de inscripcion no es válido"
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

    public function getVerifyInscriptionAdmissionProcess(){
        $activeAdmissionProcess = new AdmissionProccessDAO();
        $idAdmissionProcess = $activeAdmissionProcess->getVerifyAdmissionProcess();

        $dataIdInscriptionAdmissionProcess = $this->getIdInscriptionAdmissionProcess($idAdmissionProcess);
        if ( $dataIdInscriptionAdmissionProcess['status'] != 'success') {
            echo  $dataIdInscriptionAdmissionProcess['message'];
            return false;
        } 
        $idInscriptionAdmissionProcess = $dataIdInscriptionAdmissionProcess['id_inscription_admission_process'];

        $dataStartInscriptionAdmissionProcess = $this->getStartDateInscriptionAdmissionProcess($idInscriptionAdmissionProcess);
        if ( $dataStartInscriptionAdmissionProcess['status'] != 'success') {
            echo   $dataStartInscriptionAdmissionProcess['message'];
            return false;
        } 
        $startDateInscriptionAdmissionProcess =  new DateTime($dataStartInscriptionAdmissionProcess['startDate']);
     

        $dataEndDateInscriptionAdmissionProcess = $this->getEndDateInscriptionAdmissionProcess($idInscriptionAdmissionProcess);
        if ($dataEndDateInscriptionAdmissionProcess['status'] != 'success') {
            echo  $dataEndDateInscriptionAdmissionProcess['message'];
            return false;
        } 
        $endtDateInscriptionAdmissionProcess =   new DateTime($dataEndDateInscriptionAdmissionProcess['endDate']);

        $currentDate = new DateTime();
        if ($currentDate >= $startDateInscriptionAdmissionProcess && $currentDate <= $endtDateInscriptionAdmissionProcess) {
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