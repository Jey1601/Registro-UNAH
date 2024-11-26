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
    private $user = 'root';
    private $password = '12345';
    private $dbName = 'unah_registration';
    private $connection;

    /**
     * Constructor de clase donde se hace la conexion con la base de datos.
     * 
     * @return mysqli $connection Objeto mysqli que contiene la conexion con la base de datos, o valor null en caso de fallo.
     */
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
            //Busqueda del usuario en la base de datos
            $query = "SELECT id_user_admissions_administrator FROM UsersAdmissionsAdministrator WHERE username_user_admissions_administrator=? AND password_user_admissions_administrator=?;";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('ss', $user, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0) { //Si es mayor que 0 es porque la consulta encontro un registro, o sea, ese usuario con esa contrasena existe.
                //Para obtener un array con los IDs de los controles de accesos que tiene el usuario que se autentica 
                $row = $result->fetch_array();
                $auxID = $row[0];
                $queryAccessArray = "CALL SP_GET_ACCESS_CONTROL_USER_ADMISSION_ADMIN_BY_ID(?)";
                $stmtAccessArray = $this->connection->prepare($queryAccessArray);
                $stmtAccessArray->bind_param('i', $auxID);
                $stmtAccessArray->execute();
                $resultAccessArray = $stmtAccessArray->get_result();
                $accessArray = $resultAccessArray->fetch_array();
                $resultAccessArray->free();
                $stmtAccessArray->close(); 

                while ($this->connection->more_results() && $this->connection->next_result()) {
                    $extraResult = $this->connection->store_result();
                    if ($extraResult) {
                        $extraResult->free();
                    }
                }
                //Creacion del payload con el username y el arreglo de accesos del usuario administrador de admisiones
                $payload = [
                    'userAdmissionAdmin' => $user,
                    'accessArray' => $accessArray
                ];
                $newToken = JWT::generateToken($payload); //Generacion del token a partir del payload

                //Insercion del token en la tabla relacional entre token y usuario administrador de admisiones
                $queryUpdate = "UPDATE `TokenUserAdmissionAdmin` SET token = ? WHERE id_user_admissions_administrator = ?;";
                $stmtUpdate = $this->connection->prepare($queryUpdate);
                $stmtUpdate->bind_param('si', $newToken, $auxID);
                $resultUpdate = $stmtUpdate->execute();

                if ($resultUpdate === false) { //Si la insercion falla
                    return $response = [
                        'success' => false,
                        'message' => 'Token no registrado.'
                    ];
                }
                $stmtUpdate->close();
                
                $response = [ //Si todo funciona se retorna un arreglo asociativo donde va el token
                    'success' => true,
                    'message' => 'Validacion de credenciales exitosa.',
                    'token' => $newToken
                ];
            } else { //En caso de que no se encuentre el usuario con esa contrasena
                $response = [
                    'success' => false,
                    'message' => 'Usuario y/o contrasena incorrectos.',
                    'token' => null
                ];
            }
        } else { //En caso de que el username y la password sean nulos
            $response = [
                'success' => false,
                'message' => 'Credenciales invalidas.',
                'token' => null
            ];
        }

        return $response;
    }

    /**
     * Obtener el Identificador de un Usuario Administrador basandose en su Nombre de usuario, 
     * usando el procedimiento almacenado USER_ADMIN_BY_USERNAME(); Debe devolver un solo registro, que contenga el id del usuario administrador.
     * 
     * @param string $userNameAdmin Nombre del usuario administrador.
     * @return array Resultado de la operación con un estado ('status') y un mensaje o datos asociados:
     *               - 'success': Operación exitosa, incluye el identificador del administrador.
     *               - 'not_found': No se encontró un único usuario con el nombre de usuario especificado.
     *               - 'error': Error durante la ejecución del procedimiento o una excepción capturada.
     */
    public function getUserAdminId ($userNameAdmin){
        try {
            if (!is_string($userNameAdmin)) {
                throw new InvalidArgumentException("No se ha ingresado el parámetro correcto, debe ser una cadena de texto (varchar).");
            }            
            $IdUserAdmin = $this->connection->execute_query("CALL USER_ADMIN_BY_USERNAME('$userNameAdmin')");

            if ($IdUserAdmin) { 
                if ( $IdUserAdmin->num_rows == 1) { 
                    $IdUserAdmissionAdministrator=  $IdUserAdmin->fetch_assoc();
                    return [
                        "status" => "success",
                        "IdUserAdmissionAdministrator" => $IdUserAdmissionAdministrator['id_user_admissions_administrator']
                    ];
                } else {
                    return [
                        "status" => "not_found",
                        "message" => "Se encontraron mas de un usuario"
                    ];
                }
            } else {
                return [
                    "status" => "error",
                    "message" => "Error en el procedimiento USER_ADMIN_BY_USERNAME(): " . $this->connection->error
                ];
            }
        } catch (Exception $exception) {
            return [
                "status" => "error",
                "message" => "Excepción en getUserAdminId() capturada: " . $exception->getMessage(),
                "code" => $exception->getCode()
            ];
        }
    }
    public function getPendingCheckApplicant($userId){ 
        try {
            if (!is_int($userId)) {
                throw new InvalidArgumentException("No se ha ingresado el parámetro correcto, debe ser un entero.");
            }           
            $applicantsCheckPending = $this->connection->execute_query("CALL CHECK_PENDING_BY_USER_ADMINISTRADOR($userId)");
            if ($applicantsCheckPending) { 
                if ( $applicantsCheckPending->num_rows > 0) {
                    while ($applicant = $applicantsCheckPending->fetch_assoc()) {
                        $dataApplicantsCheckPending[] = $applicant;
                    }
                    return [
                        "status" => "success",
                        "AllApplicantsCheckPending" => $dataApplicantsCheckPending
                    ];
                } else {
                    return [
                        "status" => "warring",
                        "message" => "Se encontraron aspirantes pendientes de revision"
                    ];
                }
            } else {
                return [
                    "status" => "error",
                    "message" => "Error en el procedimiento CHECK_PENDING_BY_USER_ADMINISTRADOR(): " . $this->connection->error
                ];
            }
        } catch (Exception $exception) {
            return [
                "status" => "error",
                "message" => "Excepción en getPendingCheckApplicant() capturada: " . $exception->getMessage(),
                "code" => $exception->getCode()
            ];
        }
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
            $headers = ["id_aspirante", "num_aplicacion","id_examen", "tipo_examen", "nota_examen", "nombre_completo", "centro_regional"];

            while (($row = fgetcsv($handle, 0, ',')) !== FALSE) {
                if($firstRow) {
                    if($row == $headers) {
                        $firstRow = false;
                        continue;
                    } else {
                        $errors[] = "Error en la lectura de las cabeceras.";
                        return $response = [
                            'success' => false,
                            'message' => 'Cabeceras de CSV erroneas.',
                            'errors' => $errors
                        ];
                        break;
                    }
                    
                }
                
                //Escapar los valors para prevenir inyecciones SQL
                $idApplicant = $this->connection->real_escape_string($row[0]);
                $idAdmissionApplicationNumber = $this->connection->real_escape_string($row[1]);
                $idTypeAdmissionTest = $this->connection->real_escape_string($row[2]);
                $nameTypeAdmissionTest = $this->connection->real_escape_string($row[3]);
                $ratingApplicant = $this->connection->real_escape_string($row[4]);
                $fullNameApplicant = $this->connection->real_escape_string($row[5]);
                $nameRegionalCenter = $this->connection->real_escape_string($row[6]);

                $idAdmissionNumber = intval($idAdmissionApplicationNumber);
                $idAdmissionTest = intval($idTypeAdmissionTest);
                $rating = floatval($ratingApplicant);

                //Actualizar datos la linea
                $queryInsert = "UPDATE RatingApplicantsTest SET rating_applicant = ? WHERE id_admission_application_number = ? AND id_type_admission_tests=?";
                $insertStmt = $this->connection->prepare($queryInsert);
                $insertStmt->bind_param('dii', $rating, $idAdmissionNumber, $idAdmissionTest);
                $result = $insertStmt->execute();
                if ($result) {
                    $rowsInserted++;
                } else {
                    $errors[] = "Error en el update de la aplicacion: ".$idAdmissionNumber;
                }
            }

            //Una vez las notas están actualizadas el sistema puede dictaminar a los estudiantes.
            $this->makeResolutions();
            fclose($handle);
            $response = [
                'success' => true,
                'message' => "Calificaciones cargadas satisfactoriamente <br> Numero de filas actualizas: $rowsInserted",
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

    /**
     * Esta función obtiene la información de las carreras de las solicitudes a las que
     * aplicó un aspirante, los datos de los examenes que necesita cada carrera, y las notas 
     * obtenidas por solicitud en cada tipo de examen, compara dichos datos y asigna una 
     * resolución true or false a la carrera.
     *  
     *  @return boolean especifica si todo salió bien o no
     */
    private function makeResolutions()
    {
        // Extrae la solicitudes activas  de los aspirantes y las carreras a las que optó.
        $queryApplications = "SELECT A.id_admission_application_number, A.intendedprimary_undergraduate_applicant as id_undergraduate, A.id_admission_process, A.id_applicant
                            FROM `Applications` A
                            WHERE
                                A.status_application = 1
                            UNION
                            SELECT A.id_admission_application_number, A.intendedsecondary_undergraduate_applicant as id_undergraduate, A.id_admission_process,  A.id_applicant
                            FROM `Applications` A
                            WHERE
                                A.status_application = 1";

        // Preparar la consulta
        $stmt = $this->connection->prepare($queryApplications);
        if (!$stmt) {
            // Si hay un error preparando la consulta
            return false;
        }

        $stmt->execute();
        $result = $stmt->get_result();




        // Almacenamos todas las solicitudes de aplicación para recorrelas posteriomente
        $applications = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {



                $application = [
                "id_applicant" => $row["id_applicant"],
                "id_admission_process" => $row['id_admission_process'],
                    "id_admission_application_number" => $row['id_admission_application_number'],
                    "id_undergraduate" => $row['id_undergraduate'],
                    "resolution_intended" => 1 // Esta varible nos permitirá almacenar el valor de la carrera.
                ];
                
    
                // Añadimos cada fila al array
                $applications[] = $application;
            }
        }

        // Si no hay resultados, devolver false
        if (empty($applications)) {
            return false;
        }






        // preparamos la segunda consulta para obtener el rating necesario por cada tipo de examen en cada carrera

        $queryUndergraduatesTypesTest = "SELECT A.id_undergraduate, A.id_type_admission_tests, A.required_rating
                                            FROM
                                                `UndergraduateTypesAdmissionTests` A";


        // Preparar la consulta
        $stmt = $this->connection->prepare($queryUndergraduatesTypesTest);
        if (!$stmt) {
            // Si hay un error preparando la consulta
            return false;
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Almacenamos la información para recorrerla posterioemnete
        $undergradutesRequiredRating = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $RequiredRating = [
                    "id_undergraduate" => $row['id_undergraduate'],
                    "id_type_admission_tests" => $row['id_type_admission_tests'],
                    "required_rating" => $row['required_rating'],
                ];

                // Añadimos cada fila al array
                $undergradutesRequiredRating[] = $RequiredRating;
            }
        }

        // Si no hay resultados, devolver false
        if (empty($undergradutesRequiredRating)) {
            return false;
        }




        // preparamos la tercera consulta, que me devuelve los resultados obtenidos por el aspirante

        $queryRatings = "SELECT A.id_admission_application_number, B.id_type_admission_tests, B.rating_applicant
                                        FROM
                                            `Applications` A
                                            LEFT JOIN `RatingApplicantsTest` B ON A.id_admission_application_number = B.id_admission_application_number
                                        WHERE
                                            A.status_application = 1
                                        ORDER BY
                                            id_admission_application_number;";


        // Preparar la consulta
        $stmt = $this->connection->prepare($queryRatings);
        if (!$stmt) {
            // Si hay un error preparando la consulta
            return false;
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Almacenamos la información para recorrerla posterioemnete
        $ratings = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {



                $rating = [
                    "id_admission_application_number" => $row['id_admission_application_number'],
                    "id_type_admission_tests" => $row['id_type_admission_tests'],
                    "rating_applicant" => $row['rating_applicant'],
                ];

                // Añadimos cada fila al array
                $ratings[] = $rating;
            }
        }

        // Si no hay resultados, devolver false
        if (empty($ratings)) {
            return false;
        }



        foreach ($applications as $key => $application) {
            $applications[$key]['resolution_intended'] = 1;
        
            foreach ($undergradutesRequiredRating as $requiredRating) {
                if ($application['id_undergraduate'] == $requiredRating['id_undergraduate']) {
                    foreach ($ratings as $rating) {
                        if (
                            $rating['id_type_admission_tests'] == $requiredRating['id_type_admission_tests'] &&
                            $rating['id_admission_application_number'] == $application['id_admission_application_number']
                        ) {
                            if ($rating['rating_applicant'] < $requiredRating['required_rating']) {
                                $applications[$key]['resolution_intended'] = 0;
                                break 2;
                            }
                        }
                    }
                }
            }
        }




        // Definir la consulta de inserción
        $insertQuery = "INSERT   INTO `ResolutionIntendedUndergraduateApplicant` (
                                        id_admission_application_number,
                                        intended_undergraduate_applicant,
                                        resolution_intended,
                                        status_resolution_intended_undergraduate_applicant
                                    )
                                values (?,?,?,?);";
        $insertStmt = $this->connection->prepare($insertQuery);
        if (!$insertStmt) {
            // Si hay un error preparando la consulta de inserción
            return false;
        }

        //Valor por defecto, indica que sus notas son validas
        $status_resolution_intended_undergraduate_applicant = 1;
        // Insertar los resultados en la tabla RatingApplicantsTest
        foreach ($applications as $application) {
            $id_applicant = $application['id_applicant'];
            $id_admission_process = $application['id_admission_process'];
            $insertStmt->bind_param("iiii",  $application['id_admission_application_number'], $application['id_undergraduate'], $application['resolution_intended'], $status_resolution_intended_undergraduate_applicant);
            if (!$insertStmt->execute()) {
            
            return false;
            }else{
            //id de la resolución
            $lastInsertId = $this->connection->insert_id;

            if(!$this->createNotification( $lastInsertId)){
                return false;
            }else{
                //id de la notificación guardada
                $lastInsertId = $this->connection->insert_id;
                if(!$this->createApplicantAcceptance($lastInsertId,$id_applicant, $id_admission_process)){
                    return false;
                }
            }
            
            
            }
        }
        // Si todas las inserciones fueron exitosas, devolver true
        return true;
    }


    private function createNotification($lastInsertId): bool{
    date_default_timezone_set('America/Tegucigalpa');
    $currentDate = date("Y-m-d");  
    $emailSent = 0;
    $notificationQuery = "INSERT INTO `NotificationsApplicationsResolution` (id_resolution_intended_undergraduate_applicant,email_sent_application_resolution,date_email_sent_application_resolution) VALUES(?,?,?)";

    $insertNotStmt = $this->connection->prepare($notificationQuery);
    $insertNotStmt->bind_param("iis", $lastInsertId, $emailSent, $currentDate);
    
    if(!$insertNotStmt->execute()){
        return false;
    }

    return true;
    }

    private function createApplicantAcceptance($lastInsertId, $id_applicant, $id_admission_process): bool{
    date_default_timezone_set('America/Tegucigalpa');
    $currentDate = date("Y-m-d");  //Se actualiza posteriomente cuando la acepta
    $default = false;

    $acceptanceQuery = "INSERT INTO
                            `ApplicantAcceptance` (
                                id_notification_application_resolution,
                                id_applicant,
                                date_applicant_acceptance,
                                applicant_acceptance,
                                status_applicant_acceptance,
                                id_admission_process,
                                status_admission_process
                            )
                            values (?,?,?,?,?,?,?)";

    $insertNotStmt = $this->connection->prepare($acceptanceQuery);
    $insertNotStmt->bind_param("issiiii", $lastInsertId, $id_applicant, $currentDate, $default, $default,$id_admission_process, $default );
    
    if(!$insertNotStmt->execute()){
        return false;
    }

    return true;
    }
}
?>


