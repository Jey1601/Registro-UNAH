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
     * @return array $response Arreglo asociativo con resultado del procesamiento del archivo
    */
    public function readCSVFile($csvData) {
        $lines = explode(PHP_EOL, $csvData); //Cada linea del csv se convierte en un elemento del array
        $firstRow = true; //Para identificar la primera linea como cabeceras
        $rowsInserted = 0; //Contador de registros insertados
        $errors = [];

        foreach ($lines as $line) {
            $row = str_getcsv($line); //Convertir la linea de datos en un array (el caracter de separacion es la coma)

            if($firstRow) {
                $firstRow = false;
                continue;
            }

            if(count($row) < 6) { //Para verificar que la linea tenga al menos 6 campos
                $errors[] = "Registro con menos de 6 campos";
                continue;
            }

            //Escapar los valors para prevenir inyecciones SQL
            $idApplicant = $this->connection->real_escape_string($row[0]);
            $idAdmissionApplicationNumber = $this->connection->real_escape_string($row[1]);
            $nameTypeAdmissionTest = $this->connection->real_escape_string($row[2]);
            $ratingApplicant = $this->connection->real_escape_string($row[3]);
            $fullNameApplicant = $this->connection->real_escape_string($row[4]);
            $nameRegionalCenter = $this->connection->real_escape_string($row[5]);


            $idAdmissionNumber = intval($idAdmissionApplicationNumber);
            $rating = floatval($ratingApplicant);
            $status = 1;

            //Obtener el ID del tipo de examen y convertirlo a entero
            $queryTypeTest = "SELECT id_type_admission_tests FROM TypesAdmissionTests WHERE name_type_admission_tests = ?";
            $typetestStmt = $this->connection->prepare($queryTypeTest);
            $typetestStmt->bind_param('s',$nameTypeAdmissionTest);
            if($typetestStmt->execute()) {
                $result = $typetestStmt->get_result();
                $resultArray = $result->fetch_assoc();
                if (count($resultArray) > 0) {
                    $idTypeAdmissionTest = intval($resultArray['id_type_admission_tests']);
                } else {
                    $errors[] = "Tipo de examen no encontrado para: ".$idApplicant;
                    continue;
                }
            } else {
                $errors[] = "Error en la consulta de busqueda del tipo de examen de: ".$idApplicant;
                continue;
            }

            //Insertar la linea
            $queryInsert = "UPDATE RatingApplicantsTest SET id_admission_tests = ?, rating_applicant = ?, status_rating_applicant_test = ? WHERE id_admission_application_number = ?";
            $insertStmt = $this->connection->prepare($queryInsert);
            $insertStmt->bind_param('idii', $idTypeAdmissionTest, $rating, $status, $idAdmissionNumber);
            $result = $insertStmt->execute();
            if ($result) {
                $rowsInserted++;
            } else {
                $errors[] = "Error en el update de la aplicacion: ".$idAdmissionNumber;
            }
        }
        
        $response = [
            'success' => true,
            'message' => "Numero de filas insertadas: $rowsInserted",
            'errors' => $errors
        ];
        
        return $response;
    }
}

?>