<?php


Class AdmissionProccessDAO{
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
            printf("Failed connection: %s\n", $error->getMessage());
        }  
    }

    /**
     * Obtiene el ID del proceso de admisión activo desde la base de datos.
     *
     * Este método ejecuta un procedimiento almacenado llamado "ACTIVE_ADMISSION_PROCESS()"
     * y retorna el ID del proceso activo si existe.
     *
     * @return array Devuelve un arreglo asociativo con el ID del proceso activo o un mensaje de error:
     *               - Si se encuentra un proceso activo:
     *                 [
     *                   "status" => "success",
     *                   "id_admission_process" => int
     *                 ]
     *               - Si no hay procesos activos:
     *                 [
     *                   "status" => "not_found",
     *                   "message" => "No se encontraron procesos de admisión activos"
     *                 ]
     *               - Si ocurre un error:
     *                 [
     *                   "status" => "error",
     *                   "message" => string
     *                 ]
     */
    public function getAdmissionProcess(){
        try {
            $year = date("Y"); //obtener año actual
            $ActivesAdmissionProcess = $this->connection->execute_query("CALL ACTIVE_ADMISSION_PROCESS($year)");

            if ($ActivesAdmissionProcess) { //Comprobar si el procedimiento almacenado se ejecuto correctamente. 
                if ($ActivesAdmissionProcess->num_rows > 0) { //Comprobar si se devolvieron más de 0 proceso de admisión activo.
                    $ActiveAdmissionProcess= $ActivesAdmissionProcess->fetch_assoc(); //obtener el primer proceso de admisión activo devuelto, como un arreglo asociativo.
                    return [
                        "status" => "success",
                        "id_admission_process" => $ActiveAdmissionProcess['id_admission_process']
                    ];
                } else {
                    return [
                        "status" => "not_found",
                        "message" => "No se encontraron procesos de admisión activos"
                    ];
                }
            } else {
                return [
                    "status" => "error",
                    "message" => "Error en el procedimiento ACTIVE_ADMISSION_PROCESS(): " . $this->connection->error
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
     * Obtiene la fecha de inicio del proceso de admisión basado en el ID del proceso de admisión.
     *
     * Este método consulta la base de datos utilizando una procedimiento  almacenado llamado `START_DATE_ADMISSION_PROCESS`
     * para obtener la fecha de inicio de un proceso de admisión. Si el ID proporcionado es válido y la consulta se
     * realiza con éxito, se devuelve la fecha de inicio. Si no se encuentra la fecha, se devuelve un mensaje de error
     * indicando que no se encontró la fecha. Si el ID de admisión no es válido, se devuelve un mensaje de error.
     *
     * @param int $idAdmissionProcess El ID del proceso de admisión.
     *
     * @return array devuelve Un arreglo asociativo con el estado de la operación y los resultados:
     * 
     *   -Si el ID de admisión es válido y se encuentra la fecha de inicio*
     *   [
     *       "status" => "success",     
     *       "startDate" => $startDate    
     *   ]
     *   
        
     *   -Si el ID de admisión es válido pero no se encuentra la fecha de inicio**
     *   [
     *       "status" => "not_found",   
     *       "message" => "No se encontró la fecha de acuerdo al ID de admisión proporcionado" 
     *   ]

     *   - Si el ID de admisión no es válido
     *   [
     *       "status" => "error",    
     *       "message" => "El ID de admisión no es válido" 
     *   ]    

     *   -Si ocurre una excepción durante la ejecución de la consulta**
     *   [
     *       "status" => "error",      
     *       "message" => "Excepción capturada: " . $exception->getMessage(), 
     *       "Code" => $exception->getCode()  
     *   ]
     */
    public function getStartDateAdmissionProcess($idAdmissionProcess){
        try{
            if($idAdmissionProcess){
                $year = date("Y"); //obtener año actual
                $startDate = $this->connection->execute_query("CALL START_DATE_ADMISSION_PROCESS($idAdmissionProcess, $year)");
                if($startDate){
                    $rowStartDate = $startDate->fetch_assoc();
                    return [
                        "status" => "success",
                        "startDate" => $rowStartDate['start_dateof_admission_process']
                    ];
                }else{
                    return [
                        "status" => "not_found",
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
     * Obtiene la fecha de finalizacion del proceso de admisión basado en el ID del proceso de admisión.
     *
     * Este método consulta la base de datos utilizando una procedimiento  almacenado llamado `END_DATE_ADMISSION_PROCESS`
     * para obtener la fecha de finalizacion de un proceso de admisión. Si el ID proporcionado es válido y la consulta se
     * realiza con éxito, se devuelve la fecha de finalizacion. Si no se encuentra la fecha, se devuelve un mensaje de error
     * indicando que no se encontró la fecha. Si el ID de admisión no es válido, se devuelve un mensaje de error.
     *
     * @param int $idAdmissionProcess El ID del proceso de admisión.
     *
     * @return array devuelve Un arreglo asociativo con el estado de la operación y los resultados:
     * 
     *   -Si el ID de admisión es válido y se encuentra la fecha de finalizacion*
     *   [
     *       "status" => "success",     
     *       "endDate" => $endDate    
     *   ]
     *   
        
     *   -Si el ID de admisión es válido pero no se encuentra la fecha de finalización**
     *   [
     *       "status" => "not_found",   
     *       "message" => "No se encontró la fecha de acuerdo al ID de admisión proporcionado" 
     *   ]

     *   - Si el ID de admisión no es válido 
     *   [
     *       "status" => "error",    
     *       "message" => "El ID de admisión no es válido" 
     *   ]    

     *   -Si ocurre una excepción durante la ejecución de la consulta**
     *   [
     *       "status" => "error",     
     *       "message" => "Excepción capturada: " . $exception->getMessage(), 
     *       "Code" => $exception->getCode()  
     *   ]
     */
    public function getEndDateAdmissionProcess($idAdmissionProcess){
        try{
            if($idAdmissionProcess){
                $year = date("Y"); //obtener año actual
                $endDate = $this->connection->execute_query("CALL END_DATE_ADMISSION_PROCESS($idAdmissionProcess, $year)");
                if($endDate){
                    $rowEndDate = $endDate->fetch_assoc();
                    return [
                        "status" => "success",
                        "endDate" => $rowEndDate['end_dateof_admission_process']
                    ];
                }else{
                    return [
                        "status" => "not_found",
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
     * Obtiene el nombre del proceso de admisión basado en el ID del proceso de admisión.
     *
     * Este método consulta la base de datos utilizando una procedimiento  almacenado llamado `NAME_ADMISSION_PROCESS`
     * para obtener el nombre  de un proceso de admisión. Si el ID proporcionado es válido y la consulta se
     * realiza con éxito, se devuelve el nombre del proceso de admisión. Si no se encuentra el nombre, se devuelve un mensaje de error
     * indicando que no se encontró el nombre. Si el ID de admisión no es válido, se devuelve un mensaje de error. 
     *
     * @param int $idAdmissionProcess El ID del proceso de admisión.
     *
     * @return array devuelve Un arreglo asociativo con el estado de la operación y los resultados:
     * 
     *   -Si el ID de admisión es válido y se encuentra el nombre del proceso de admision*
     *   [
     *       "status" => "success",     
     *       "nameAdmissionProcess" => $nameAdmissionProcess   
     *   ]
     *   
        
     *   -Si el ID de admisión es válido pero no se encuentra lel nombre del proceso de admision**
     *   [
     *       "status" => "not_found",   
     *       "message" => "No se encontro el nombre de acuerdo al ID de admisión proporcionado" 
     *   ]

     *   - Si el ID de admisión no es válido
     *   [
     *       "status" => "error",    
     *       "message" => "El ID de admisión no es válido" 
     *   ]    

     *   -Si ocurre una excepción durante la ejecución de la consulta**
     *   [
     *       "status" => "error",   
     *       "message" => "Excepción capturada: " . $exception->getMessage(), 
     *       "Code" => $exception->getCode()  
     *   ]
     */
    public function getNameAdmissionProcess($idAdmissionProcess){
        try{
            if($idAdmissionProcess){
                $year = date("Y"); //obtener año actual
                $namesAdmissionProcess = $this->connection->execute_query("CALL NAME_ADMISSION_PROCESS($idAdmissionProcess, $year)");
                if($namesAdmissionProcess){
                    $nameAdmissionProcess = $namesAdmissionProcess->fetch_assoc();
                    return [
                        "status" => "success",
                        "nameAdmissionProcess" => $nameAdmissionProcess['name_admission_process']
                    ];
                }else{
                    return [
                        "status" => "not_found",
                        "message" => "No se encontro el nombre de acuerdo al id de admision proporcionado"
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
                "Code" => $exception->getCode()
            ];
        }
    }

    public function getVerifyAdmissionProcess(){
        $dataIdAdmissionProcess = $this->getAdmissionProcess();
        if ( $dataIdAdmissionProcess['status'] != 'success') {
            echo  $dataIdAdmissionProcess['message'];
            return false;
        } 
        $idAdmissionProcess = $dataIdAdmissionProcess['id_admission_process'];

        $dataStartAdmissionProcess = $this->getStartDateAdmissionProcess($idAdmissionProcess);
        if ( $dataStartAdmissionProcess['status'] != 'success') {
            echo   $dataStartAdmissionProcess['message'];
            return false;
        } 
        $startDateAdmissionProcess =  new DateTime($dataStartAdmissionProcess['startDate']);
     

        $dataEndDateAdmissionProcess = $this->getEndDateAdmissionProcess($idAdmissionProcess);
        if ($dataEndDateAdmissionProcess['status'] != 'success') {
            echo  $dataEndDateAdmissionProcess['message'];
            return false;
        } 
        $endtDateAdmissionProcess =   new DateTime($dataEndDateAdmissionProcess['endDate']);

        $currentDate = new DateTime();
        if ($currentDate >= $startDateAdmissionProcess && $currentDate <= $endtDateAdmissionProcess) {
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