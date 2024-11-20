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

include_once 'util/jwt.php';
class AdmissionAdminDAO {
    private $host = 'localhost';
    private $user = 'prueba';
    private $password = '123';
    private $dbName = 'unah_registration';
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
     * @return array $response Arreglo asociativo con resultado de la autenticacion, mensaje descriptivo y nuevo token (o token nulo en caso de fallo de autenticacion)
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
     * @param file $csvFile Archivo CSV
     * 
     * @return array $response Arreglo asociativo con resultado del procesamiento del archivo
    */
    public function readCSVFile($csvFile) {
        $fileTmpPath = $csvFile['tmp_name'];

        if (($handle = fopen($fileTmpPath, 'r')) !== FALSE) {
            $firstRow = true; //Para identificar la primera linea como cabeceras
            $rowsInserted = 0; //Contador de registros insertados
            $errors = [];

            while (($row = fgetcsv($handle, 0, ',')) !== FALSE) {
                if($firstRow) {
                    $firstRow = false;
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

                //Actualizar datos la linea
                $queryInsert = "UPDATE RatingApplicantsTest SET rating_applicant = ? WHERE id_admission_application_number = ?";
                $insertStmt = $this->connection->prepare($queryInsert);
                $insertStmt->bind_param('di', $rating, $idAdmissionNumber);
                $result = $insertStmt->execute();
                if ($result) {
                    $rowsInserted++;
                } else {
                    $errors[] = "Error en el update de la aplicacion: ".$idAdmissionNumber;
                }
            }

            fclose($handle);
            $response = [
                'success' => true,
                'message' => "Numero de filas insertadas: $rowsInserted",
                'errors' => $errors
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Error al abrir el archivo CSV.'
            ];
        }
        
        return $response;
    }
}

?>