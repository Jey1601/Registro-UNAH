<?php


Class  ClassSectionsDaysDAO{
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
            printf("Conexion Fallida en ClassSectionsDaysDAO: %s\n", $error->getMessage());
        }  
    }
    
    /**
     * Asigna días específicos a una sección de clase utilizando el procedimiento almacenado 
     * INSERT_CLASS_SECTION_DAY.
     *
     * @param int   $newClassSectionId ID de la nueva sección de clase.
     * @param array $days              Array de días a asignar a la sección (por ejemplo: ['Lunes', 'Miercoles']).
     *
     * @return array Resultado de la operación:
     *               - status: 'success' si la operación fue exitosa, 'error' en caso de falla.
     *               - message: Detalle del resultado de la operación.
     *
     * @throws InvalidArgumentException Si los parámetros proporcionados no son válidos.
     * 
     * @author Alejandro Moya
     * @created 07/12/2024
     */
    public function createClassSectionsDays($newClassSectionId, $days){
        if (
            !is_int($newClassSectionId) 
        ) {
            throw new InvalidArgumentException("Parámetros inválidos en: createClassSection().");
        }
        try {
            $status_class_section = 1;
            foreach ($days as $day) {
                $stmtDay = $this->connection->prepare("CALL INSERT_CLASS_SECTION_DAY(?, ?, ?)");
                $stmtDay->bind_param("isi", $newClassSectionId, $day, $status_class_section);
                $stmtDay->execute();
                $stmtDay->close();
            }
            return [
                "status" => "success",
                "message" => "Los dias de la seccion se asignaron correctamente."
            ];
        } catch (Exception $e) {
            // Manejo de excepciones
            return [
                "status" => "error",
                "message" => "Error en createClassSectionsDays() al procesar la solicitud: " . $e->getMessage()
            ];
        }
    }

    // Método para cerrar la conexión (opcional), 
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

}
?>