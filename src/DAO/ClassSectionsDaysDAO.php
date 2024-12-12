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
     * Obtiene los días asignados a una sección de clase específica cuando están activos.
     *
     * Este método utiliza un procedimiento almacenado para obtener los días de clase asociados 
     * a una sección específica, basado en el ID proporcionado.
     *
     * @param int $idClassSection ID de la sección de clase.
     *
     * @return array Resultado de la operación:
     *               - "status" => "success" en caso de éxito.
     *               - "days" => Lista de días con su estado asociado.
     * @throws InvalidArgumentException Si los parámetros no son válidos.
     * @throws mysqli_sql_exception Si ocurre un error en la ejecución del procedimiento almacenado.
     *
     * @author Alejandro Moya
     * @created 08/12/2024
     */
    public function getClassSectionDays($idClassSection){
        if (!is_int($idClassSection)) {
            throw new InvalidArgumentException("El parámetro debe ser un entero válido en getClassSectionDays().");
        }

        try {
            $stmt = $this->connection->prepare("CALL GET_CLASS_SECTION_DAYS(?)");
            if ($stmt === false) {
                throw new mysqli_sql_exception("Error al preparar la consulta: " . $this->connection->error);
            }
            $stmt->bind_param("i", $idClassSection);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result === false) {
                throw new mysqli_sql_exception("Error al obtener los resultados: " . $stmt->error);
            }

            $days = [];
            while ($row = $result->fetch_assoc()) {
                $days[] = $row['id_day'];
            }

            $stmt->close();
            $daysClassSection = implode(", ", $days);
            return [
                "status" => "success",
                "days" => $daysClassSection
            ];
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception("Error al ejecutar GET_CLASS_SECTION_DAYS(): " . $e->getMessage());
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
