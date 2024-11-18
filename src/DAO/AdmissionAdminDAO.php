<?php
/**
 * Controlador de Administrador de admisiones
 * 
 * @property string $host Direccion de host de base de datos
 * @property string $user Usuario de acceso a la base de datos
 * @property string $password Clave de acceso del respectivo usuario
 * @property string $dbName Nombre de la base de datos
 * @property mysqli $connection Objeto de conexion con la base de datos
*/
include_once '../Registro-UNAH/src/util/jwt.php';

class AdmissionAdminDAO {
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $dbName = 'Registro-UNAH';
    private $connection;

    public function __construct () {
        $this->connection = null;
        try {
            $this->connection = new mysqli($this->host, $this->user, $this->password, $this->dbName);
        } catch (Exception $error) {
            printf("Failed connection: %s\n", $error->getMessage());
        }

        return $this->connection;
    }

    /** 
     * Metodo para autenticacion de Administrador de admisiones
     * 
     * @param string $user Nombre de usuario del Administrador de admisiones
     * @param string $password Contrasena del respectivo usuario
     * 
     * @return Array $response Arreglo asociativo con resultado de la autenticacion, mensaje descriptivo y nuevo token (o token nulo en caso de fallo de autenticacion)
    */
    public function authAdmissionAdmin (string $user, string $password) {
        if (isset($user) && isset($password)) {
            $query = "SELECT username_user_admissions_administrator, password_user_admissions_administrator FROM UsersAdmissionsAdministrator WHERE username_user_admissions_administrator=? AND password_user_admissions_administrator=?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('ss', $user, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0) {
                $payload = [
                    'userAdmissionAdmin' => $user,
                    'passwordAdmissionAdmin' => $password
                ];
                $newToken = JWT::generateToken($payload);

                $response = [
                    'success' => true,
                    'message' => 'Validacion de credenciales exitosa.',
                    'token' => $newToken
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Usuario y/o contrasena incorrectos.',
                    'token' => null
                ];
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Credenciales invalidas.',
                'token' => null
            ];
        }

        return $response;
    }

    /** 
     * Metodo para leer un archivo CSV subido por el Administrador de admisiones
     * 
     * @param string $csvData Texto del archivo CSV
     * 
     * @return array $response Arreglo asociativo con resultado del procesamiento del archivo y mensaje descriptivo indicando el numero de filas insertadas en la base de datos
    */
    public function readCSVFile($csvData, bool $headers) {
        $lines = explode(PHP_EOL, $csvData); //Cada linea del csv se convierte en un elemento del array
        $firstRow = $headers; //Para identificar la primera linea como cabeceras
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
            'success' => true,
            'message' => "Numero de filas insertadas: $rowsInserted"
        ];
        
        return $response;
    }
}

?>