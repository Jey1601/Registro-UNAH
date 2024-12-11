<?php


Class EnrollmentProcessDAO{
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
     * Verifica si existe un proceso de matrícula.
     *
     * Este método ejecuta un procedimiento almacenado llamado "CHECK_ENROLLMENT_PROCESS_STATUS"
     * y retorna el estado del proceso si existe.
     *
     * @return array Devuelve un arreglo asociativo con el estado del proceso activo o un mensaje de error:
     *               - Si se encuentra un proceso activo:
     *                 [
     *                   "status" => "success",
     *                   "process_exists" => true
     *                 ]
     *               - Si no hay procesos activos:
     *                 [
     *                   "status" => "not_found",
     *                   "process_exists" => false
     *                 ]
     *               - Si ocurre un error:
     *                 [
     *                   "status" => "error",
     *                   "message" => string
     *                 ]
     * @author Alejandro Moya 20211020462
     * @created 09/12/2024
     */
    public function checkEnrollmentProcessStatus(){
        try {
            $query = "CALL CHECK_ENROLLMENT_PROCESS_STATUS();";
            $stmt = $this->connection->prepare($query);

            if (!$stmt) {
                throw new Exception('Error al preparar la consulta: ' . $this->connection->error);
            }

            if (!$stmt->execute()) {
                throw new Exception('Error al ejecutar el procedimiento almacenado: ' . $stmt->error);
            }
            $stmt->close();
            $result = $this->connection->query("SELECT @process_exists AS process_exists;");

            if ($result) {
                $row = $result->fetch_assoc();
                if($row['process_exists'] == 1){
                    return [
                        "status" => "success",
                        "process_exists" => true
                    ];
                }else{
                    return [
                        "status" => "warning",
                        "process_exists" => false
                    ];
                }
            } else {
                return [
                    "status" => "error",
                    "message" => "No se pudo obtener el estado del proceso de matrícula."
                ];
            }
        } catch (Exception $exception) {
            return [
                "status" => "error",
                "message" => "Excepción capturada en checkEnrollmentProcessStatus(): " . $exception->getMessage(),
                "code" => $exception->getCode()
            ];
        }
    }

    /**
     * Verifica si existe un proceso de matrícula activo.
     *
     * Este método ejecuta un procedimiento almacenado que retorna el  id del proceso de matrícula activo, si el estado del proceso  es true.
     *
     * @return array Devuelve un arreglo asociativo con el estado del proceso activo o un mensaje de error:
     *               - Si se encuentra un proceso activo:
     *                 [
     *                   "status" => "success",
     *                   "process_exists" => true,
     *                   "id_enrollment_process" => int
     *                 ]
     *               - Si no hay procesos activos:
     *                 [
     *                   "status" => "not_found",
     *                   "process_exists" => false
     *                 ]
     *               - Si ocurre un error:
     *                 [
     *                   "status" => "error",
     *                   "message" => string
     *                 ]
     * @author Alejandro Moya 20211020462
     * @created 09/12/2024
     */
    public function checkActiveEnrollmentProcess() {
        try {
            $query = "CALL GET_ACTIVE_ENROLLMENT_PROCESS();";
            $stmt = $this->connection->prepare($query);

            if (!$stmt) {
                throw new Exception('Error al preparar la consulta: ' . $this->connection->error);
            }

            if (!$stmt->execute()) {
                throw new Exception('Error al ejecutar el procedimiento almacenado: ' . $stmt->error);
            }
            $stmt->store_result(); 
            $stmt->bind_result($id_enrollment_process);
            if ($stmt->fetch()) {
                return [
                    "status" => "success",
                    "process_exists" => true,
                    "id_enrollment_process" => $id_enrollment_process
                ];
            } else {
                return [
                    "status" => "not_found",
                    "process_exists" => false
                ];
            }
        } catch (Exception $exception) {
            return [
                "status" => "error",
                "message" => "Excepción capturada en checkEnrollmentProcessStatus(): " . $exception->getMessage(),
                "code" => $exception->getCode()
            ];
        }
    }

   
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

}