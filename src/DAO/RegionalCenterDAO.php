<?php


Class RegionalCenterDAO{

    private $host = 'localhost';
    private $user = 'root';
    private $password = '12345';
    private $dbName = 'unah_registration';
    private $connection;
    public function __construct()
    {
        
        try {
            $this->connection = new mysqli($this->host, $this->user, $this->password, $this->dbName);
        } catch (Exception $error) {
            printf("Failed connection: %s\n", $error->getMessage());
        }
      
    }

    /**
     * Obtiene todos los centros regionales desde la base de datos.
     *
     * Este método ejecuta una consulta a la base de datos para obtener todos los centros regionales 
     * registrados en la tabla `RegionalCenters` y los devuelve como un array de resultados.
     *
     * @return array Un array de centros regionales, donde cada elemento del array contiene los datos 
     *               de un centro regional.
     *
     * @throws Exception Si ocurre un error durante la ejecución de la consulta.
     */
    
     public function getRegionalCenters() {
        $regionalCenters = [];
    
        // Ejecutamos la consulta
        $result = $this->connection->query("SELECT * FROM RegionalCenters;");

        // Verificamos si la consulta fue exitosa
        if ($result) {
            // Recorremos los resultados y los agregamos al array $regionalCenters
            while ($row = $result->fetch_assoc()) {
                // Añadimos cada fila al array
                $regionalCenters[] = $row;
            }
        } else {
            // Si hubo un error con la consulta
            printf("Error in query: %s\n", $this->connection->error);
        }

        // Retornamos el array con los centros regionales
        return $regionalCenters;
    }
/**
 * Obtiene los centros regionales asociados a un departamento específico.
 *
 * Esta función realiza una consulta a la base de datos para recuperar
 * todos los centros regionales activos vinculados a un departamento
 * identificado por su ID.
 *
 * @param int $id_department El ID del departamento para el cual se desean obtener los centros regionales.
 * @return array $regionalCenters Un array asociativo que contiene los centros regionales.
 * @throws Exception Si ocurre un error al preparar o ejecutar la consulta SQL.
 */
    public function getRegionalCentersByDepartment($id_department) {
        // Inicializamos el array donde se almacenarán los resultados
        $regionalCenters = [];

        // Definimos la consulta SQL para obtener los centros regionales
        $query = "SELECT B.name_regional_center, B.id_regional_center 
                FROM `DepartmentsRegionalCenters` A
                LEFT JOIN `RegionalCenters` B ON A.id_regionalcenter = B.id_regional_center
                WHERE A.id_department = ? AND A.status_department_regional_center = 1;";

        // Preparamos la consulta SQL
        $stmt = $this->connection->prepare($query);

        // Verificamos si la preparación de la consulta fue exitosa
        if ($stmt === false) {
            die("Error al preparar la consulta: " . $this->connection->error);
        }

        // Asociamos el parámetro $id_department al marcador de posición en la consulta
        $stmt->bind_param("i", $id_department);

        // Ejecutamos la consulta preparada
        if (!$stmt->execute()) {
            die("Error al ejecutar la consulta: " . $stmt->error);
        }

        // Obtenemos el resultado de la consulta
        $result = $stmt->get_result();

        // Verificamos si el resultado no es falso
        if ($result) {
            // Iteramos sobre cada fila del resultado
            while ($row = $result->fetch_assoc()) {
                // Agregamos cada fila al array de centros regionales
                $regionalCenters[] = $row;
            }
        } else {
            // Imprimimos un mensaje de error si la consulta falló
            printf("Error en la consulta: %s\n", $this->connection->error);
        }

        // Retornamos el array con los centros regionales obtenidos
        return $regionalCenters;
    }
    
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }







}