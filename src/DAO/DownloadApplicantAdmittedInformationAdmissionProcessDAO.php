<?php
include_once "AdmissionProccessDAO.php";

Class DownloadApplicantAdmittedInformationAdmissionProcessDAO{
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
     * Obtiene el ID del subproceso de descarga de aspirantes admitidos activo ,que pertenece al proceso de admision especificado, desde la base de datos.
     *
     * Este método ejecuta un procedimiento almacenado llamado "ACTIVE_DOWNLOAD_ADMITTED()"
     * y retorna el ID del subproceso de descarga de aspirantes admitidos activo si existe.
     *
     * @param int $idAdmissionProcess El ID  del proceso de admision para el cual se desea obtener el subproceso de descarga de aspirantes admitidos activo.
     * 
     * @return array Devuelve un arreglo asociativo con el resultado de la consulta:
     *               - Si se encuentra un subproceso activo:
     *                 [
     *                   "status" => "success",
     *                   "id_download_applicant_information_admission_process" => int
     *                 ]
     *               - Si no se encuentra un subproceso activo:
     *                 [
     *                   "status" => "not_found",
     *                   "message" => "No se encontraron subsubprocesos de descarga de aspirantes admitidos activos"
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
    public function getIdDownloadApplicantAdmittedInformationAdmissionProcess($idAdmissionProcess){
        try {
            $idAdmissionProcess = (int) $idAdmissionProcess; //Se asegura que se maneje como entero
            $IdsDownloadAdmitted = $this->connection->execute_query("CALL ACTIVE_DOWNLOAD_ADMITTED($idAdmissionProcess)");

            if ($IdsDownloadAdmitted) { //Comprobar si el procedimiento almacenado se ejecuto correctamente. 
                if ($IdsDownloadAdmitted->num_rows > 0) { //Comprobar si se devolvieron más de 0 identificadores de subproceso de descarga de aspirantes admitidos.
                    $IdDownloadAdmitted= $IdsDownloadAdmitted->fetch_assoc(); //obtener el primer identificadores de subproceso de descarga de aspirantes admitidos activo devuelto, como un arreglo asociativo.
                    return [
                        "status" => "success",
                        "id_download_applicant_information_admission_process" => $IdDownloadAdmitted['id_download_applicant_information_admission_process']
                    ];
                } else {
                    return [
                        "status" => "not_found",
                        "message" => "No se encontraron subsubprocesos de descarga de aspirantes admitidos activos"
                    ];
                }
            } else {
                return [
                    "status" => "error",
                    "message" => "Error en el procedimiento ACTIVE_DOWNLOAD_ADMITTED(): " . $this->connection->error
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
     * Obtiene la fecha de inicio del subproceso de descarga de aspirantes admitidos basado en el ID del del subproceso de descarga de aspirantes admitidos.
     *
     * Este método consulta la base de datos utilizando una procedimiento  almacenado llamado `START_DATE_DOWNLOAD_ADMITTED()`
     * para obtener la fecha de inicio del subproceso de descarga de aspirantes admitidos. Si el ID proporcionado es válido y la consulta se
     * realiza con éxito, se devuelve la fecha de inicio. Si no se encuentra la fecha, se devuelve un mensaje de error
     * indicando que no se encontró la fecha. Si el ID del subproceso de descarga de aspirantes admitidos no es válido, se devuelve un mensaje de error.
     *
     * @param int $idDownloadAdmitted El ID del subproceso de descarga de aspirantes admitidos.
     *
     * @return array devuelve Un arreglo asociativo con el estado de la operación y los resultados:
     * 
     *   -Si el ID del subproceso de descarga de aspirantes admitidos es válido y se encuentra la fecha de inicio
     *   [
     *       "status" => "success",     
     *       "startDate" => $startDate    
     *   ]
     *   
        
     *   -Si el ID del subproceso de descarga de aspirantes admitidos es válido pero no se encuentra la fecha de inicio
     *   [
     *       "status" => "not_found",   
     *       "message" => "No se encontró la fecha de acuerdo al ID del subproceso de descarga de aspirantes admitidos proporcionado" 
     *   ]

     *   - Si el ID del subproceso de descarga de aspirantes admitidos no es válido
     *   [
     *       "status" => "error",    
     *       "message" => "El ID del subproceso de descarga de aspirantes admitidos no es válido" 
     *   ]    

     *   -Si ocurre una excepción durante la ejecución de la consulta
     *   [
     *       "status" => "error",      
     *       "message" => "Excepción capturada: " . $exception->getMessage(), 
     *       "code" => $exception->getCode()  
     *   ]
     */
    public function getStartDateDownloadApplicantAdmittedInformationAdmissionProcess($idDownloadAdmitted){
        try{
            if($idDownloadAdmitted){
                $startDates = $this->connection->execute_query("CALL START_DATE_DOWNLOAD_ADMITTED($idDownloadAdmitted)");
                if($startDates){
                    $startDate = $startDates->fetch_assoc();
                    return [
                        "status" => "success",
                        "startDate" => $startDate['start_dateof_download_applicant_information_admission_process']
                    ];
                }else{
                    return [
                        "status" => "not_found",
                        "message" => "No se encontro la fecha de acuerdo al id del subproceso de descarga de aspirantes admitidos proporcionado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "message" => "El ID del subproceso de descarga de aspirantes admitidos no es valido"
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
     * Obtiene la fecha de finalizacion del subproceso de descarga de aspirantes admitidos basado en el ID del subproceso de descarga de aspirantes admitidos.
     *
     * Este método consulta la base de datos utilizando una procedimiento  almacenado llamado `END_DATE_DOWNLOAD_ADMITTED()`
     * para obtener la fecha de finalizacion de un subproceso de descarga de aspirantes admitidos. Si el ID proporcionado es válido y la consulta se
     * realiza con éxito, se devuelve la fecha de finalizacion. Si no se encuentra la fecha, se devuelve un mensaje de error
     * indicando que no se encontró la fecha. Si el idDownloadAdmitted no es válido, se devuelve un mensaje de error.
     *
     * @param int $idDownloadAdmitted El ID del subproceso de descarga de aspirantes admitidos.
     *
     * @return array devuelve Un arreglo asociativo con el estado de la operación y los resultados:
     * 
     *   -Si el ID del subproceso de descarga de aspirantes admitidos es válido y se encuentra la fecha de finalizacion
     *   [
     *       "status" => "success",     
     *       "endDate" => $endDate    
     *   ]
     *   
        
     *   -Si el ID del subproceso de descarga de aspirantes admitidos es válido pero no se encuentra la fecha de finalización
     *   [
     *       "status" => "not_found",   
     *       "message" => "No se encontró la fecha de acuerdo al ID del subproceso de descarga de aspirantes admitidos" 
     *   ]

     *   - Si el ID del subproceso de descarga de aspirantes admitidos no es válido 
     *   [
     *       "status" => "error",    
     *       "message" => "El ID del subproceso de descarga de aspirantes admitidos no es válido" 
     *   ]    

     *   -Si ocurre una excepción durante la ejecución de la consulta
     *   [
     *       "status" => "error",     
     *       "message" => "Excepción capturada: " . $exception->getMessage(), 
     *       "code" => $exception->getCode()  
     *   ]
     */
    public function getEndDateDownloadApplicantAdmittedInformationAdmissionProcess($idDownloadAdmitted){
        try{
            if($idDownloadAdmitted){
                $endDates = $this->connection->execute_query("CALL END_DATE_DOWNLOAD_ADMITTED($idDownloadAdmitted)");
                if($endDates){
                    $endDate = $endDates->fetch_assoc();
                    return [
                        "status" => "success",
                        "endDate" => $endDate['end_dateof_download_applicant_information_admission_process']
                    ];
                }else{
                    return [
                        "status" => "not_found",
                        "message" => "No se encontro la fecha deacuerdo al id del subproceso de descarga de aspirantes admitidos proporcionado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "message" => "El ID del subproceso de descarga de aspirantes admitidos no es válido"
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

    public function getVerifyDownloadApplicantAdmittedInformationAdmissionProcess(){
        $activeAdmissionProcess = new AdmissionProccessDAO();
        $idAdmissionProcess = $activeAdmissionProcess->getVerifyAdmissionProcess();

        $dataIdDownloadApplicantAdmittedInformationAdmissionProcess = $this->getIdDownloadApplicantAdmittedInformationAdmissionProcess($idAdmissionProcess);
        if ( $dataIdDownloadApplicantAdmittedInformationAdmissionProcess['status'] != 'success') {
            echo  $dataIdDownloadApplicantAdmittedInformationAdmissionProcess['message'];
            return false;
        } 
        $idDownloadApplicantAdmittedInformationAdmissionProcess = $dataIdDownloadApplicantAdmittedInformationAdmissionProcess['id_download_applicant_information_admission_process'];

        $dataStartDownloadApplicantAdmittedInformationAdmissionProcess = $this->getStartDateDownloadApplicantAdmittedInformationAdmissionProcess($idDownloadApplicantAdmittedInformationAdmissionProcess);
        if ( $dataStartDownloadApplicantAdmittedInformationAdmissionProcess['status'] != 'success') {
            echo   $dataStartDownloadApplicantAdmittedInformationAdmissionProcess['message'];
            return false;
        } 
        $startDateDownloadApplicantAdmittedInformationAdmissionProcess =  new DateTime($dataStartDownloadApplicantAdmittedInformationAdmissionProcess['startDate']);
     

        $dataEndDateDownloadApplicantAdmittedInformationAdmissionProcess = $this->getEndDateDownloadApplicantAdmittedInformationAdmissionProcess($idDownloadApplicantAdmittedInformationAdmissionProcess);
        if ($dataEndDateDownloadApplicantAdmittedInformationAdmissionProcess['status'] != 'success') {
            echo  $dataEndDateDownloadApplicantAdmittedInformationAdmissionProcess['message'];
            return false;
        } 
        $endtDateDownloadApplicantAdmittedInformationAdmissionProcess =   new DateTime($dataEndDateDownloadApplicantAdmittedInformationAdmissionProcess['endDate']);

        $currentDate = new DateTime();
        if ($currentDate >= $startDateDownloadApplicantAdmittedInformationAdmissionProcess && $currentDate <= $endtDateDownloadApplicantAdmittedInformationAdmissionProcess) {
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