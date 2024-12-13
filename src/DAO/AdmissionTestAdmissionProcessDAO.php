<?php
include_once "AdmissionProccessDAO.php";

Class AdmissionTestAdmissionProcessDAO{
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
     * Obtiene el ID del subproceso de examen de admision activo ,que pertenece al proceso de admision especificado, desde la base de datos.
     *
     * Este método ejecuta un procedimiento almacenado llamado "ACTIVE_ADMISSION_TEST()"
     * y retorna el ID del subproceso de examen de admision activo, si existe.
     *
     * @param int $idAdmissionProcess El ID  del proceso de admision para el cual se desea obtener el subproceso de examen de admision activo.
     * 
     * @return array Devuelve un arreglo asociativo con el resultado de la consulta:
     *               - Si se encuentra un proceso activo:
     *                 [
     *                   "status" => "success",
     *                   "id_admission_test_admission_process" => int
     *                 ]
     *               - Si no se encuentra un proceso activo:
     *                 [
     *                   "status" => "not_found",
     *                   "message" => "No se encontraron subprocesos de examenes de admision activos"
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
    public function getIdAdmissionTestAdmissionProcess($idAdmissionProcess){
        try {
            $idAdmissionProcess = (int) $idAdmissionProcess; //Se asegura que se maneje como entero
            $IdsAdmissionTest = $this->connection->execute_query("CALL ACTIVE_ADMISSION_TEST($idAdmissionProcess)");

            if ($IdsAdmissionTest) { //Comprobar si el procedimiento almacenado se ejecuto correctamente. 
                if ($IdsAdmissionTest->num_rows > 0) { //Comprobar si se devolvieron más de 0 identificadores de subproceso de examen de admision.
                    $IdAdmissionTest= $IdsAdmissionTest->fetch_assoc(); //obtener el primer identificadores de subproceso de examen de admision activo devuelto, como un arreglo asociativo.
                    return [
                        "status" => "success",
                        "id_admission_test_admission_process" => $IdAdmissionTest['id_admission_test_admission_process']
                    ];
                } else {
                    return [
                        "status" => "not_found",
                        "message" => "No se encontraron subprocesos de examenes de admision activos"
                    ];
                }
            } else {
                return [
                    "status" => "error",
                    "message" => "Error en el procedimiento ACTIVE_ADMISSION_TEST(): " . $this->connection->error
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
     * Obtiene la fecha  del  subproceso de examen de admision basado en el ID  del subproceso de examen de admision.
     *
     * Este método consulta la base de datos utilizando una procedimiento  almacenado llamado `DATE_ADMISSION_TEST()`
     * para obtener la fecha del subproceso de examen de admision. Si el ID proporcionado es válido y la consulta se
     * realiza con éxito, se devuelve la fecha. Si no se encuentra la fecha, se devuelve un mensaje de error
     * indicando que no se encontró la fecha. Si el ID del subproceso de examen de admision no es válido, se devuelve un mensaje de error.
     *
     * @param int $idAdmissionTestAdmissionProcess El ID del subproceso de examen de admision.
     *
     * @return array devuelve Un arreglo asociativo con el estado de la operación y los resultados:
     * 
     *   -Si el ID del subproceso de examen de admision es válido y se encuentra la fecha
     *   [
     *         "status" => "success",
     *         "DateTest" => $DateTest    
     *   ]
     *   
        
     *   -Si el ID del subproceso de examen de admision es válido pero no se encuentra la fecha
     *   [
     *       "status" => "not_found",   
     *       "message" => "No se encontró la fecha de acuerdo al ID del subproceso de examen de admision proporcionado" 
     *   ]

     *   - Si el ID del subproceso de examen de admision no es válido
     *   [
     *       "status" => "error",    
     *       "message" => "El ID del subproceso de examen de admision no es válido" 
     *   ]    

     *   -Si ocurre una excepción durante la ejecución de la consulta
     *   [
     *       "status" => "error",      
     *       "message" => "Excepción capturada: " . $exception->getMessage(), 
     *       "Code" => $exception->getCode()  
     *   ]
     * @author Alejandro Moya 20211020462
     * @created Noviembre de 2024
     */
    public function getDateAdmissionTestAdmissionProcess($idAdmissionTestAdmissionProcess){
        try{
            if($idAdmissionTestAdmissionProcess){
                $DateTest = $this->connection->execute_query("CALL DATE_ADMISSION_TEST($idAdmissionTestAdmissionProcess)");
                if($DateTest){
                    $rowDateTest = $DateTest->fetch_assoc();
                    return [
                        "status" => "success",
                        "DateTest" => $rowDateTest['dateof_admission_test_admission_process']
                    ];
                }else{
                    return [
                        "status" => "not_found",
                        "message" => "No se encontro la fecha de acuerdo al id del subproceso de examen de admision proporcionado"
                    ];
                }
            }else{
                return [
                    "status" => "error",
                    "message" => "El ID del subproceso de examen de admision no es valido"
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
     
}
?>
