<?php


Class CareerDAO{
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
     * Obtiene las carreras asociadas a un centro regional específico.
     *
     * Este método realiza una consulta a la base de datos para obtener todas las carreras 
     * que están asociadas a un centro regional determinado, basándose en el ID del centro regional proporcionado.
     * 
     * @param int $id_regionalCenter El ID del centro regional para el cual se buscan las carreras asociadas.
     *
     * @return array Un array de carreras asociadas al centro regional. Cada elemento del array contiene el 
     *               ID y el nombre de la carrera.
     *
     * @throws Exception Si ocurre un error durante la ejecución de la consulta o preparación.
     */
     public function getCareersBy($id_regionalCenter) {
        $careers = [];

        $query ="Select Undergraduates.id_undergraduate, Undergraduates.name_undergraduate 
                from Undergraduates INNER JOIN UndergraduatesRegionalCenters 
                ON UndergraduatesRegionalCenters.id_undergraduate = Undergraduates.id_undergraduate 
                where  UndergraduatesRegionalCenters.id_regionalcenter = ?;";

        //Se prepara la consulta
        $stmt = $this->connection->prepare($query);

        if ($stmt === false) {
            die("Error al preparar la consulta: " . $this->connection->error);
        }
        
        $stmt->bind_param("i", $id_regionalCenter); 

        // Ejecutamos la consulta
       $stmt->execute();
       $result = $stmt->get_result();

        // Verificamos si la consulta fue exitosa
        if ($result) {
            // Recorremos los resultados y los agregamos al array $Careers
            while ($row = $result->fetch_assoc()) {
                // Añadimos cada fila al array
                $careers[] = $row;
            }
        } else {
            // Si hubo un error con la consulta
            printf("Error in query: %s\n", $this->connection->error);
        }

        // Retornamos el array con los centros regionales
        return $careers;
    }

   
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

}