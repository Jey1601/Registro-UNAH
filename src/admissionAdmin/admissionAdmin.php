<?php
class AdmissionAdminDAO {
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $dbName = 'Registro_UNAH';
    private $connection;

    public function __construct()
    {
        $this->connection = null;
        try {
            $this->connection = new mysqli($this->host, $this->user, $this->password, $this->dbName);
        } catch (Exception $error) {
            printf("Failed connection: %s\n", $error->getMessage());
        }

        return $this->connection;
    }

    public function validateAdmissionAdmin(array $token) {
        
    }

    public function readCSVFile($csvData) {
        $lines = explode(PHP_EOL, $csvData); //Cada linea del csv se convierte en un elemento del array
        $firstRow = true; //Para identificar la primera linea como cabeceras
        $rowsInserted = 0; //Contador de registros insertados

        foreach ($lines as $line) {
            $row = str_getcsv($line); //Convertir la linea de datos en un array (el caracter de separacion es la coma)

            if($firstRow) {
                $firstRow = false;
                continue;
            }

            if(count($row) < 4) { //Para verificar que la linea tenga al menos 4 campos: id_applicant, id_admission_aplication_number, name_type_admission_tests, rating_applicant
                continue;
            }

            //Escapar los valors para prevenir inyecciones SQL
            $id_applicant = $this->connection->real_escape_string($row[0]);
            $id_admission_aplication_number = $this->connection->real_escape_string($row[1]);
            $name_type_admission_tests = $this->connection->real_escape_string($row[2]);
            $rating_applicant = $this->connection->real_escape_string($row[3]);
            
            $idAdmissionNumber = intval($id_admission_aplication_number);
            $rating = floatval($rating_applicant);
            $satus = 1; //TEMPORAL

            //Obtener el ID del tipo de examen y convertirlo a entero
            $queryTypeTest = "SELECT id_type_admission_tests FROM TypesAdmissionTests WHERE name_type_admission_tests = ?";
            $typetestStmt = $this->connection->prepare($queryTypeTest);
            $typetestStmt->bind_param('s',$name_type_admission_tests);
            $typetestStmt->execute();
            $idTypeTest = intval($typetestStmt->get_result());

            //Insertar la linea
            $queryInsert = "INSERT INTO RatingApplicantsTest VALUES (?,?,?,?,?);";
            $insertStmt = $this->connection->prepare($queryInsert);
            $insertStmt->bind_param('iidi',$idAdmissionNumber, $idTypeTest, $rating, $status);
            $result = $insertStmt->execute();
            if ($result) {
                $rowsInserted++;
            }

            
        }
        
        $response = [

            'httpCode' => http_response_code(200),
            'message' => "Numero de filas insertadas: $rowsInserted"
        ];
        
        return $response;
    }

}

?>