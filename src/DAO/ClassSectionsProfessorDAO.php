<?php


Class  ClassSectionsProfessorDAO{
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
            printf("Conexion Fallida en ClassSectionsProfessorDAO: %s\n", $error->getMessage());
        }  
    }

    /**
     * Obtiene la URL del video de presentación de una clase para una sección específica y un profesor.
     *
     * Este método utiliza un procedimiento almacenado para obtener el video de presentación asociado a una 
     * sección de clase y profesor, basado en los IDs proporcionados.
     *
     * @param int $idClassSection ID de la sección de clase.
     * @param int $idProfessor    ID del profesor.
     *
     * @return array Resultado de la operación:
     *               - "status" => "success" en caso de éxito.
     *               - "urlVideo" => La URL del video de presentación.
     * @throws InvalidArgumentException Si los parámetros no son válidos.
     * @throws mysqli_sql_exception Si ocurre un error en la ejecución del procedimiento almacenado.
     *
     * @author Alejandro Moya
     * @created 08/12/2024
     */
    public function getUrlPresentationVideo($idClassSection, $idProfessor){
        if (!is_int($idClassSection) || !is_int($idProfessor)) {
            throw new InvalidArgumentException("Los parámetros deben ser enteros válidos en getUrlPresentationVideo().");
        }

        try {
            $stmt = $this->connection->prepare("CALL GET_CLASS_PRESENTATION_VIDEO(?, ?)");
            if ($stmt === false) {
                throw new mysqli_sql_exception("Error al preparar la consulta: " . $this->connection->error);
            }
            $stmt->bind_param("ii", $idClassSection, $idProfessor);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result === false) {
                throw new mysqli_sql_exception("Error al obtener los resultados: " . $stmt->error);
            }
            $row = $result->fetch_assoc();
            if (!$row) {
                throw new mysqli_sql_exception("No se encontró un video de presentación para los parámetros proporcionados.");
            }
            $urlVideo = $row['class_presentation_video'];
            $stmt->close();
            return [
                "status" => "success",
                "urlVideo" => $urlVideo
            ];
        } catch (mysqli_sql_exception $e) {
            throw new mysqli_sql_exception("Error al ejecutar GET_CLASS_PRESENTATION_VIDEO: " . $e->getMessage());
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
