<?php


Class RegionalCenterDAO{


    public function __construct(string $host, string $username, string $password, string $dbName)
    {
        $this->connection = null;
        try {
            $this->connection = new mysqli($host, $username, $password, $dbName);
        } catch (Exception $error) {
            printf("Failed connection: %s\n", $error->getMessage());
        }

        return $this->connection;
    }

     // Método para obtener los centros regionales
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

    // Método para cerrar la conexión (opcional)
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }







}