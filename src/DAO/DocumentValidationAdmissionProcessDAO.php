<?php
include_once "AdmissionProccessDAO.php";

Class DocumentValidationAdmissionProcessDAO{
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
     * Obtiene el ID del subproceso de validacion de documentos activo, que pertenece al proceso de admision especificado, desde la base de datos.
     *
     * Este método ejecuta un procedimiento almacenado llamado "ACTIVE_DOCUMENT_VALIDATION"
     * y devuelve un arreglo con el ID del subproceso de validación de documentos activo,
     * si existe, o un mensaje de error si no se encuentra.

     *
     * @param int $idAdmissionProcess El ID  del proceso de admision para el cual se desea obtener el subproceso de validacion de documentos activo.
     * 
     * @return array Devuelve un arreglo asociativo con el resultado de la consulta:
     *               - Si se encuentra un proceso activo:
     *                 [
     *                   "status" => "success",
     *                   "id_document_validation_admission_process" => int
     *                 ]
     *               - Si no se encuentra un proceso activo:
     *                 [
     *                   "status" => "not_found",
     *                   "message" => "No se encontraron subprocesos de validacion de documentos activos"
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
    public function getIdDocumentValidationAdmissionProcess($idAdmissionProcess){
        try {
            $idAdmissionProcess = (int) $idAdmissionProcess;
            $IdsDocumentValidationAdmissionProcess = $this->connection->execute_query("CALL ACTIVE_DOCUMENT_VALIDATION($idAdmissionProcess)");

            if ($IdsDocumentValidationAdmissionProcess) { //Comprobar si el procedimiento almacenado se ejecuto correctamente. 
                if ($IdsDocumentValidationAdmissionProcess->num_rows > 0) { //Comprobar si se devolvieron más de 0 identificadores de subproceso de validacion de documentos.
                    $IdDocumentValidationAdmissionProcess= $IdsDocumentValidationAdmissionProcess->fetch_assoc(); //obtener el primer identificadores de subproceso de validacion de documentos activo devuelto, como un arreglo asociativo.
                    return [
                        "status" => "success",
                        "id_document_validation_admission_process" => $IdDocumentValidationAdmissionProcess['id_document_validation_admission_process']
                    ];
                } else {
                    return [
                        "status" => "not_found",
                        "message" => "No se encontraron subprocesos de validacion de documentos activos"
                    ];
                }
            } else {
                return [
                    "status" => "error",
                    "message" => "Error en el procedimiento ACTIVE_DOCUMENT_VALIDATION(): " . $this->connection->error
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
     * Obtiene la fecha de inicio del  subproceso de validacion de documentos basado en el ID del del subproceso de validacion de documentos.
     *
     * Este método consulta la base de datos utilizando una procedimiento  almacenado llamado `START_DATE_DOCUMENT_VALIDATION()`
     * para obtener la fecha de inicio del subproceso de validacion de documentos. Si el ID proporcionado es válido y la consulta se
     * realiza con éxito, se devuelve la fecha de inicio. Si no se encuentra la fecha, se devuelve un mensaje de error
     * indicando que no se encontró la fecha. Si el ID del subproceso de validacion de documentos no es válido, se devuelve un mensaje de error.
     *
     * @param int $idDocumentValidationAdmissionProcess El ID del subproceso de validacion de documentos.
     *
     * @return array devuelve Un arreglo asociativo con el estado de la operación y los resultados:
     * 
     *   -Si el ID del subproceso de validacion de documentos es válido y se encuentra la fecha de inicio
     *   [
     *       "status" => "success",     
     *       "startDate" => $startDate    
     *   ]
     *   
        
     *   -Si el ID del subproceso de validacion de documentos es válido pero no se encuentra la fecha de inicio
     *   [
     *       "status" => "not_found",   
     *       "message" => "No se encontró la fecha de acuerdo al ID del subproceso de validacion de documentos proporcionado" 
     *   ]

     *   - Si el ID del subproceso de validacion de documentos no es válido
     *   [
     *       "status" => "error",    
     *       "message" => "El ID del subproceso de validacion de documentos no es válido" 
     *   ]    

     *   -Si ocurre una excepción durante la ejecución de la consulta
     *   [
     *       "status" => "error",      
     *       "message" => "Excepción capturada: " . $exception->getMessage(), 
     *       "Code" => $exception->getCode()  
     *   ]
     */
    public function getStartDateDocumentValidationAdmissionProcess($idDocumentValidationAdmissionProcess){
        try{
            if($idDocumentValidationAdmissionProcess){
                $startDates = $this->connection->execute_query("CALL START_DATE_DOCUMENT_VALIDATION($idDocumentValidationAdmissionProcess)");
                if($startDates){
                    $startDate = $startDates -> fetch_assoc();
                    return [
                        "status" => "success",
                        "startDate" => $startDate['start_dateof_document_validation_admission_process']
                    ];
                }else{
                    return [
                        "status" => "not_found",
                        "message" => "No se encontro la fecha de acuerdo al id del subproceso de validacion de documentos proporcionado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "message" => "El ID del subproceso de validacion de documentos no es valido"
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
     * Obtiene la fecha de finalizacion del subproceso de validacion de documentos basado en el ID del subproceso de validacion de documentos.
     *
     * Este método consulta la base de datos utilizando una procedimiento  almacenado llamado `END_DATE_DOCUMENT_VALIDATION()`
     * para obtener la fecha de finalizacion de un subproceso de validacion de documentos. Si el ID proporcionado es válido y la consulta se
     * realiza con éxito, se devuelve la fecha de finalizacion. Si no se encuentra la fecha, se devuelve un mensaje de error
     * indicando que no se encontró la fecha. Si el idDocumentValidationAdmissionProcess no es válido, se devuelve un mensaje de error.
     *
     * @param int $idDocumentValidationAdmissionProcess El ID del subproceso de validacion de documentos.
     *
     * @return array devuelve Un arreglo asociativo con el estado de la operación y los resultados:
     * 
     *   -Si el ID del subproceso de validacion de documentos es válido y se encuentra la fecha de finalizacion
     *   [
     *       "status" => "success",     
     *       "endDate" => $endDate    
     *   ]
     *   
        
     *   -Si el ID del subproceso de validacion de documentos es válido pero no se encuentra la fecha de finalización
     *   [
     *       "status" => "not_found",   
     *       "message" => "No se encontró la fecha de acuerdo al ID del subproceso de validacion de documentos" 
     *   ]

     *   - Si el ID del subproceso de validacion de documentos no es válido 
     *   [
     *       "status" => "error",    
     *       "message" => "El ID del subproceso de validacion de documentos no es válido" 
     *   ]    

     *   -Si ocurre una excepción durante la ejecución de la consulta
     *   [
     *       "status" => "error",     
     *       "message" => "Excepción capturada: " . $exception->getMessage(), 
     *       "code" => $exception->getCode()  
     *   ]
     */
    public function getEndDateDocumentValidationAdmissionProcess($idDocumentValidationAdmissionProcess){
        try{
            if($idDocumentValidationAdmissionProcess){
                $endDates = $this->connection->execute_query("CALL END_DATE_DOCUMENT_VALIDATION($idDocumentValidationAdmissionProcess)");
                if($endDates){
                    $endDate = $endDates->fetch_assoc();
                    return [
                        "status" => "success",
                        "endDate" => $endDate['end_dateof_document_validation_admission_process']
                    ];
                }else{
                    return [
                        "status" => "not_found",
                        "message" => "No se encontro la fecha deacuerdo al id del subproceso de validacion de documentos proporcionado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "message" => "El ID del subproceso de validacion de documentos no es válido"
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


    public function getVerifyDocumentValidationAdmissionProcess(){
        $activeAdmissionProcess = new AdmissionProccessDAO();
        $idAdmissionProcess = $activeAdmissionProcess->getVerifyAdmissionProcess();

        $dataIdDocumentValidationAdmissionProcess = $this->getIdDocumentValidationAdmissionProcess( $idAdmissionProcess);
        if ( $dataIdDocumentValidationAdmissionProcess['status'] != 'success') {
            echo  $dataIdDocumentValidationAdmissionProcess['message'];
            return false;
        } 
        $idDocumentValidationAdmissionProcess = $dataIdDocumentValidationAdmissionProcess['id_document_validation_admission_process'];

        $dataStartDocumentValidationAdmissionProcess = $this->getStartDateDocumentValidationAdmissionProcess($idDocumentValidationAdmissionProcess);
        if ( $dataStartDocumentValidationAdmissionProcess['status'] != 'success') {
            echo   $dataStartDocumentValidationAdmissionProcess['message'];
            return false;
        } 
        $startDateDocumentValidationAdmissionProcess =  new DateTime($dataStartDocumentValidationAdmissionProcess['startDate']);
     

        $dataEndDateDocumentValidationAdmissionProcess = $this->getEndDateDocumentValidationAdmissionProcess($idDocumentValidationAdmissionProcess);
        if ($dataEndDateDocumentValidationAdmissionProcess['status'] != 'success') {
            echo  $dataEndDateDocumentValidationAdmissionProcess['message'];
            return false;
        } 
        $endtDateDocumentValidationAdmissionProcess =   new DateTime($dataEndDateDocumentValidationAdmissionProcess['endDate']);

        $currentDate = new DateTime();
        if ($currentDate >= $startDateDocumentValidationAdmissionProcess && $currentDate <= $endtDateDocumentValidationAdmissionProcess) {
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