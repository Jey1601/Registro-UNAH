<?php


Class DepartmentDAO{
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
 * Obtiene los departamentos asociados a una facultad específica.
 *
 * Esta función realiza una consulta a la base de datos para recuperar
 * todos los departamentos activos (status_department = 1) vinculados
 * a una facultad identificada por su ID.
 *
 * @param int $id_faculty El ID de la facultad para la cual se desean obtener los departamentos.
 * @return array $departments Un array asociativo que contiene los departamentos.
 * @throws Exception Si ocurre un error al preparar o ejecutar la consulta SQL.
 */
public function getDepartmentsByFaculty($id_faculty) {
    // Inicializamos el array donde se almacenarán los resultados
    $departments = [];

    // Definimos la consulta SQL para obtener los departamentos
    $query = "SELECT * FROM `Departments` WHERE id_faculty = ? AND status_department = 1;";

    // Preparamos la consulta SQL
    $stmt = $this->connection->prepare($query);

    // Verificamos si la preparación de la consulta fue exitosa
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $this->connection->error);
    }

    // Asociamos el parámetro $id_faculty al marcador de posición en la consulta
    $stmt->bind_param("i", $id_faculty);

    // Ejecutamos la consulta preparada
    $stmt->execute();

    // Obtenemos el resultado de la consulta
    $result = $stmt->get_result();

    // Verificamos si el resultado no es falso
    if ($result) {
        // Iteramos sobre cada fila del resultado
        while ($row = $result->fetch_assoc()) {
            // Agregamos cada fila al array de departamentos
            $departments[] = $row;
        }
    } else {
        // Imprimimos un mensaje de error si la consulta falló
        printf("Error en la consulta: %s\n", $this->connection->error);
    }

    // Retornamos el array con los departamentos obtenidos
    return $departments;
}

   
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

}