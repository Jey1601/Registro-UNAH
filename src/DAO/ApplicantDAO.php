<?php
/**
 * Controlador de Aspirantes
 * 
 * @property string $host Direccion de host de base de datos
 * @property string $user Usuario de acceso a la base de datos
 * @property string $password Clave de acceso del respectivo usuario
 * @property string $dbName Nombre de la base de datos
 * @property mysqli $connection Objeto de conexion con la base de datos
*/

include_once 'util/jwt.php';
class ApplicantDAO{
    private $host = 'localhost';
    private $user = 'prueba';
    private $password = '123';
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

    // Método para obtener los aspirantes
    public function getApplicants()
    {
        $applicants = [];

        // Ejecutamos la consulta
        $result = $this->connection->query("SELECT * FROM Applicants;");

        // Verificamos si la consulta fue exitosa
        if ($result) {
            // Recorremos los resultados y los agregamos al array $regionalCenters
            while ($row = $result->fetch_assoc()) {
                // Añadimos cada fila al array
                $applicants[] = $row;
            }
        } else {
            // Si hubo un error con la consulta
            printf("Error in query: %s\n", $this->connection->error);
        }

        return $applicants;
    }

    public function viewData()
    {
        $applicationsData = [];

        // Ejecutamos la consulta
        $result = $this->connection->query("CALL SP_ASPIRANTS_DATA_VIEW()");

        // Verificamos si la consulta fue exitosa
        if ($result) {
            // Recorremos los resultados y los agregamos al array $applicationsData
            while ($row = $result->fetch_assoc()) {

                $imageData = $row['certificate'];
                $imageType = finfo_buffer(finfo_open(), $imageData, FILEINFO_MIME_TYPE);  // Detectar tipo MIME

                // Convertir la imagen binaria a base64
                $imageBase64 = base64_encode($imageData);

                // Crear el prefijo adecuado según el tipo MIME
                $imageSrc = "data:" . $imageType . ";base64," . $imageBase64;

                // Crear un arreglo asociativo con claves más descriptivas
                $application = [
                    "id_applicant" => $row['id_applicant'],
                    "name" => $row['name'],
                    "lastname" => $row['lastname'],
                    "phone_number_applicant" => $row['phone_number_applicant'],
                    "address_applicant" => $row['address_applicant'],
                    "email_applicant" => $row['email_applicant'],
                    "id_admission_application_number" => $row['id_admission_application_number'],
                    "name_admission_process" => $row['name_admission_process'],
                    "name_regional_center" => $row['name_regional_center'],
                    "firstC" => $row['firstC'],
                    "secondC" => $row['secondC'],
                    "certificate" => $imageSrc
                ];

                // Añadimos cada fila al array
                $applicationsData[] = $application;
            }

        } else {
            // Si hubo un error con la consulta
            echo json_encode(["error" => "Error en la consulta SP_ASPIRANTS_DATA_VIEW: " . $this->connection->error]);
        }

        // Devolvemos los datos como JSON
        echo json_encode($applicationsData);
    }

    public function createInscription($id_applicant, $first_name, $second_name, $third_name, $first_lastname, $second_lastname, $email, $phone_number, $address, $status, $id_aplicant_type, $secondary_certificate_applicant, $id_regional_center, $regionalcenter_admissiontest_applicant, $intendedprimary_undergraduate_applicant, $intendedsecondary_undergraduate_applicant)
    {
        // Iniciar una transacción
        $this->connection->begin_transaction();

        try {
            // Verificamos si el aspirante ya tenía información guardada anteriormente
            if ($this->isCreated($id_applicant)) {
                // Si está creado, debemos verificar que no tenga una solicitud activa
                if ($this->hasActiveApplication($id_applicant)) {
                    // Si tiene una solicitud activa no le permitimos actualizar información ni crear una nueva
                    echo json_encode(["message" => "Usted ya está inscrito en el proceso actual"]);
                } else {
                    // Si no tiene una solicitud activa actualizamos su información
                    if (!$this->updateApplicant($id_applicant, $email, $phone_number, $address, $status)) {
                        echo json_encode(["error" => "Ha ocurrido un error en la actualización de la información"]);

                    }
                    // Creamos la nueva solicitud
                    if (!$this->createApplication($id_applicant, $id_aplicant_type, $secondary_certificate_applicant, $id_regional_center, $regionalcenter_admissiontest_applicant, $intendedprimary_undergraduate_applicant, $intendedsecondary_undergraduate_applicant)) {
                        echo json_encode(["error" => "Ha ocurrido un error al crear la solicitud"]);
                    } else {

                        echo json_encode(["message" => "Inscripción creada exitosamente"]);
                    }
                }
            } else {
                // Si no está creado hacemos el insert
                if (!$this->insertApplicant($id_applicant, $first_name, $second_name, $third_name, $first_lastname, $second_lastname, $email, $phone_number, $address, $status)) {

                    echo json_encode(["error" => "Ha ocurrido un error al guardar la información del aspirante"]);
                }

                // Creamos la nueva solicitud
                if (!$this->createApplication($id_applicant, $id_aplicant_type, $secondary_certificate_applicant, $id_regional_center, $regionalcenter_admissiontest_applicant, $intendedprimary_undergraduate_applicant, $intendedsecondary_undergraduate_applicant)) {

                    echo json_encode(["error" => "Ha ocurrido un error al crear la solicitud"]);
                } else {
                    echo json_encode(["message" => "Inscripción creada exitosamente"]);
                }
            }

            // Si todo fue exitoso, confirmamos la transacción
            $this->connection->commit();

        } catch (Exception $e) {
            // En caso de error, revertimos la transacción
            $this->connection->rollback();
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    /**
     * Metodo para autenticacion de un aspirante.
     * 
     * @param string $numID Numero de identidad del aspirante.
     * @param int $numReq Numero de solicitud del aspirante.
     * 
     * @return array $response Arreglo asociativo con resultado de la autenticacion y un token (valido en caso de exito, nulo en caso de fallo).
     */
    public function authApplicant(string $numID, int $numReq) {
        if (isset($numID) && isset($numReq)) {
            //Busca al aspirante
            $query = "SELECT id_applicant, id_admission_application_number FROM Applications WHERE id_applicant = ? AND id_admission_application_number = ?;";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('si', $numID, $numReq);
            $stmt->execute();
            $result = $stmt->get_result(); //Obtiene resultado de la consulta a la BD

            if($result->num_rows > 0) { //Verifica que la consulta no esté vacía, si lo está es que el aspirante no está registrado
                $payload = [
                    'applicantID' => $numID,
                    'numAdmissionRequest' => $numReq
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
                    'message' => 'Usuario y/o numero de solicitud no encontrados.',
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
     * Metodo para obtener numero de identidad, numero de solicitud, nombre del tipo de examen, nota del examen, nombre completo y centro regional de los aspirantes en un CSV.
     * 
     * @return string $csvContent Informacion de los aspirantes.
     */
    public function getApplicantsInfoCSV(){
        $query = "CALL SP_APPLICANTS_DATA();";
        $applicants = $this->connection->execute_query($query);

        $csvHeaders = ["id_aspirante", "num_aplicacion", "tipo_examen", "nota_examen", "nombre_completo", "centro_regional"];

        //Crear un stream en memoria para el archivo CSV
        $csvFile = fopen('php://temp', '+r');

        //Escribir las cabeceras del CSV
        fputcsv($csvFile, $csvHeaders);

        //Llenado de datos del CSV
        foreach ($applicants as $applicant) {
            fputcsv($csvFile, $applicant);
        }

        //Volver al inicio del archivo para que pueda ser enviado
        rewind($csvFile);

        //Leer el contenido del archivo CSV en memoria
        $csvContent =  stream_get_contents($csvFile);

        //Cerrar el stream
        fclose($csvFile);

        return $csvContent;
    }


    public function getResults($id_applicant){
        // Asegurarse de que la conexión esté activa
        if (!$this->connection) {
            echo json_encode(['error' => 'No hay conexión a la base']);
            return;
        }
    
        // Primera consulta: Obtener las resoluciones de aspirante
        $queryResolutions = "
            SELECT 
                C.id_applicant,
                E.id_undergraduate,
                E.name_undergraduate,
                A.resolution_intended,
                D.name_regional_center
            FROM `ResolutionIntendedUndergraduateApplicant` A
            INNER JOIN `Applications` B ON A.id_admission_application_number = B.id_admission_application_number
            INNER JOIN `Applicants` C ON B.id_applicant = C.id_applicant
            INNER JOIN `RegionalCenters` D ON B.idregional_center = D.id_regional_center
            INNER JOIN `Undergraduates` E ON A.intended_undergraduate_applicant = E.id_undergraduate
            WHERE A.status_resolution_intended_undergraduate_applicant = true
            AND B.id_applicant = ?";
    
        // Preparar la consulta y verificar errores
        $stmt = $this->connection->prepare($queryResolutions);
        if ($stmt === false) {
            echo json_encode(['error' => 'Error preparando la consulta: ' . $this->connection->error]);
            return;
        }
    
        // Vincular el parámetro y ejecutar la consulta
        $stmt->bind_param('s', $id_applicant);
        if (!$stmt->execute()) {
            echo json_encode(['error' => 'Error ejecutando la consulta  ' . $stmt->error]);
            return;
        }
    
        $resultResolutions = $stmt->get_result();
        $resolutions = [];
    
        if ($resultResolutions && $resultResolutions->num_rows > 0) {
            while ($row = $resultResolutions->fetch_assoc()) {
                $resolutions[] = [
                    "id_applicant" => $row['id_applicant'],
                    "id_undergraduate" => $row['id_undergraduate'],
                    "name_undergraduate" => $row['name_undergraduate'],
                    "resolution_intended" => $row['resolution_intended'],
                    "name_regional_center" => $row['name_regional_center'],
                ];
            }
        }
    
        // Segunda consulta: Obtener los resultados de los exámenes
        $queryResults = "
            SELECT 
                A.id_applicant, 
                CONCAT(
                    D.first_name_applicant, ' ',
                    IFNULL(D.second_name_applicant, ''), ' ',
                    IFNULL(D.third_name_applicant, ''), ' ',
                    D.first_lastname_applicant, ' ',
                    IFNULL(D.second_lastname_applicant, '')
                ) AS name,
                A.id_admission_application_number, 
                C.name_type_admission_tests, 
                B.rating_applicant
            FROM `Applicants` D
            LEFT JOIN `Applications` A ON D.id_applicant = A.id_applicant
            LEFT JOIN `RatingApplicantsTest` B ON A.id_admission_application_number = B.id_admission_application_number
            LEFT JOIN `TypesAdmissionTests` C ON B.id_type_admission_tests = C.id_type_admission_tests
            WHERE A.status_application = TRUE
            AND A.id_applicant = ?";
    
        // Preparar la consulta y verificar errores
        $stmt = $this->connection->prepare($queryResults);
        if ($stmt === false) {
            echo json_encode(['error' => 'Error preparando la consulta: ' . $this->connection->error]);
            return;
        }
    
        // Vincular el parámetro y ejecutar la consulta
        $stmt->bind_param('s', $id_applicant);
        if (!$stmt->execute()) {
            echo json_encode(['error' => 'Error excutando la consulta : ' . $stmt->error]);
            return;
        }
    
        $resultResultsTest = $stmt->get_result();
        $resultsTest = [];
    
        if ($resultResultsTest && $resultResultsTest->num_rows > 0) {
            while ($row = $resultResultsTest->fetch_assoc()) {
                $resultsTest[] = [
                    "name" => $row['name'],
                    "id_admission_application_number" => $row['id_admission_application_number'],
                    "name_type_admission_tests" => $row['name_type_admission_tests'],
                    "rating_applicant" => $row['rating_applicant'],
                ];
            }
        }
    
        // Preparar la respuesta final
        $response = [
            "resolutions" => $resolutions,
            "resultsTest" => $resultsTest
        ];
    
   
        echo json_encode($response);
    }

    // Método para insertar un nuevo aspirante
    private function insertApplicant($id_applicant, $first_name, $second_name, $third_name, $first_lastname, $second_lastname, $email, $phone_number, $address, $status)
    {

        // Preparar la consulta SQL de inserción
        $query = "INSERT INTO Applicants (id_applicant, first_name_applicant, second_name_applicant, third_name_applicant, first_lastname_applicant, second_lastname_applicant, email_applicant, phone_number_applicant, address_applicant, status_applicant) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepared statement para evitar SQL injection
        if ($stmt = $this->connection->prepare($query)) {
            // Vinculamos los parámetros a la consulta
            $stmt->bind_param("sssssssssi", $id_applicant, $first_name, $second_name, $third_name, $first_lastname, $second_lastname, $email, $phone_number, $address, $status);

            // Ejecutamos la consulta
            if ($stmt->execute()) {
                // Commit explícito de la transacción actual
                $this->connection->commit();
                $stmt->close();
                return true;
            } else {
                // Si la ejecución falla, hacemos un rollback para evitar inconsistencias
                $this->connection->rollback();
                $stmt->close();
                return false;
            }
        } else {
            // Si hay un error en la preparación de la consulta
            $this->connection->rollback();
            return false;
        }
    }

    private function updateApplicant($id_applicant, $email, $phone_number, $address, $status)
    {
        // Preparar la consulta SQL de  Actualización, solo actualizamos campos email, phone, y address 
        $query = "UPDATE Applicants 
                  SET  email_applicant = ?,
                       phone_number_applicant = ?, 
                       address_applicant = ?, 
                       status_applicant = ?
                  WHERE id_applicant = ?";

        // Prepared statement para evitar SQL injection
        if ($stmt = $this->connection->prepare($query)) {
            // Vinculamos los parámetros a la consulta
            $stmt->bind_param("sssis", $email, $phone_number, $address, $status, $id_applicant);

            // Ejecutamos la consulta
            if ($stmt->execute()) {
                $stmt->close();
                return true;
            } else {
                $stmt->close();
                return false;
            }



        } else {
            echo json_encode(["error" => "Error en la preparación de la consulta updateApplicant: " . $this->connection->error]);
        }

    }

    private function createApplication($id_applicant, $id_aplicant_type, $secondary_certificate_applicant, $id_regional_center, $regionalcenter_admissiontest_applicant, $intendedprimary_undergraduate_applicant, $intendedsecondary_undergraduate_applicant)
    {
        // Extraer el proceso de admisión activo
        $id_admission_process = $this->getAdmissionProcess();
        if (!$id_admission_process) {
            echo json_encode(["error" => "No hay un proceso de admisión activo." . $this->connection->error]);

            return false; // No se puede continuar sin un proceso activo
        }

        $status_application = 1; // Nueva solicitud, siempre activa

        // Consulta de inserción
        $query = "INSERT INTO Applications 
                  (id_admission_process, id_applicant, id_aplicant_type, secondary_certificate_applicant, idregional_center, regionalcenter_admissiontest_applicant, intendedprimary_undergraduate_applicant, intendedsecondary_undergraduate_applicant, status_application) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        if ($stmt = $this->connection->prepare($query)) {
            // Vincular parámetros
            $stmt->bind_param(
                "isisiiiii",
                $id_admission_process,
                $id_applicant,
                $id_aplicant_type,
                $secondary_certificate_applicant,
                $id_regional_center,
                $regionalcenter_admissiontest_applicant,
                $intendedprimary_undergraduate_applicant,
                $intendedsecondary_undergraduate_applicant,
                $status_application
            );

            // Ejecutar la consulta
            if ($stmt->execute()) {

                $id_application = $this->connection->insert_id;

                //Se crea el usuario del aspirante relacionado con la solicitud recien creada y los rating test
                if ($this->createUserApplicant(id_applicant: $id_applicant, id_application: $id_application) && $this->createRatingApplicantsTest($id_application)) {

                    $stmt->close();
                    return true; // Éxito
                } else {
                    echo json_encode(["error" => "Error en la creación de la solicitud: " . $stmt->error]);
                }
                ;


            } else {
                // Registrar error en la ejecución
                echo json_encode(["error" => "Error en la creación de la solicitud: " . $stmt->error]);

                $stmt->close();
                return false;
            }
        } else {
            // Registrar error en la preparación

            echo json_encode(["error" => "Error en la preparación de la consulta createApplication: " . $this->connection->error]);
            $stmt->close();
            return false;
        }
    }

    private function createUserApplicant($id_applicant, $id_application)
    {

        $status_user_applicant = 1;
        // Consulta de inserción
        $query = "INSERT INTO UsersApplicants (username_user_applicant,password_user_applicant,status_user_applicant)  VALUES (?, ?, ?)";

        // Preparar la consulta
        if ($stmt = $this->connection->prepare($query)) {
            // Vincular parámetros
            $stmt->bind_param(
                "sii",
                $id_applicant,
                $id_application,
                $status_user_applicant
            );

            // Ejecutar la consulta
            if ($stmt->execute()) {

                $stmt->close();
                return true; // Éxito
            } else {
                // Registrar error en la ejecución
                $stmt->close();

                return false;
            }
        } else {
            // Registrar error en la preparación

            $stmt->close();
            return false;
        }
    }

    private function getAdmissionProcess()
    {
        $query = "SELECT id_admission_process FROM AdmissionProcess WHERE current_status_admission_process = 1";

        // Ejecutar la consulta
        $result = $this->connection->query($query);

        if ($result) {
            // Verificar si hay resultados
            if ($result->num_rows > 0) {
                //Se devuelve el primer resultado
                $row = $result->fetch_assoc();
                return $row['id_admission_process'];
            } else {
                // No se encontraron procesos de admisión activos
                return null;
            }
        } else {
            // Registrar error y devolver null
            echo json_encode(["error" => "Error en la consulta getAdmissionProcess: " . $this->connection->error]);

            return null;
        }
    }

    private function isCreated($id_applicant)
    {
        $query = "SELECT id_applicant FROM Applicants WHERE id_applicant = ?";

        // Preparar la consulta para evitar inyecciones SQL
        if ($stmt = $this->connection->prepare($query)) {
            // Vincular los parámetros
            $stmt->bind_param("s", $id_applicant);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Almacenar el resultado
                $stmt->store_result();

                // Verificar si hay filas
                if ($stmt->num_rows > 0) {
                    $stmt->close();
                    return true; // El registro existe
                } else {
                    $stmt->close();
                    return false; // El registro no existe
                }
            } else {
                // Registrar el error de ejecución
                echo json_encode(["error" => "Error al ejecutar la consulta isCreated:  " . $stmt->error]);

                $stmt->close();
                return false;
            }
        } else {
            // Registrar el error al preparar la consulta
            echo json_encode(["error" => "Error al preparar la consulta isCreated: " . $this->connection->error]);


            return false;
        }
    }

    private function hasActiveApplication($id_applicant)
    {
        $query = "SELECT 1 FROM Applications WHERE id_applicant = ? AND status_application = 1";

        // Preparar la consulta para evitar inyecciones SQL
        if ($stmt = $this->connection->prepare($query)) {
            // Vincular los parámetros
            $stmt->bind_param("s", $id_applicant);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Almacenar el resultado
                $stmt->store_result();

                // Verificar si hay filas
                if ($stmt->num_rows > 0) {
                    $stmt->close();
                    return true; // Existe una aplicación activa
                } else {
                    $stmt->close();
                    return false; // No hay aplicaciones activas
                }
            } else {
                // Registrar el error de ejecución
                $stmt->close();
                //echo json_encode(["error" => "Error al ejecutar la consulta hasActiveApplication:"   . $stmt->error]);

                return false;
            }
        } else {
            // Registrar el error al preparar la consulta
            //echo json_encode(["error" => "Error al preparar la consulta hasActiveApplication: "   . $this->connection->error]);

            return false;
        }
    }

    /**
     * Esta función obtiene la información de los tipos de examenes que
     * debe de hacer el aspirante, luego con esa información crea un registros
     * en la tabla RatingApplicantsTest por cada test que debe realizar con la 
     * calificación default de 0.
     *  @param int $id_admission_application_number número de aplicación del aspirante
     *  @return boolean especifica si todo salió bien o no
     */
    private function createRatingApplicantsTest($id_admission_application_number)
    {
        // Definir la consulta de extracción de tipo de examenes
        $query = "SELECT id_type_admission_tests 
                  FROM `UndergraduateTypesAdmissionTests` 
                  LEFT JOIN `Applications` ON id_undergraduate = intendedprimary_undergraduate_applicant
                  WHERE id_admission_application_number = ?
                  UNION
                  SELECT id_type_admission_tests 
                  FROM `UndergraduateTypesAdmissionTests` 
                  LEFT JOIN Applications ON id_undergraduate = intendedsecondary_undergraduate_applicant
                  WHERE id_admission_application_number = ?;";

        // Preparar la consulta
        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            // Si hay un error preparando la consulta
            return false;
        }

        // Vincular parámetros
        $stmt->bind_param("ii", $id_admission_application_number, $id_admission_application_number);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si hay resultados
        $results = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $results[] = $row['id_type_admission_tests'];
            }
        }

        // Si no hay resultados, devolver false
        if (empty($results)) {
            return false;
        }

        // Definir los valores predeterminados
        $rating = 0.0;
        $status = 0;

        // Definir la consulta de inserción
        $insertQuery = "INSERT INTO `RatingApplicantsTest` (id_admission_application_number, id_type_admission_tests, rating_applicant, status_rating_applicant_test) 
                        VALUES (?, ?, ?, ?)";
        $insertStmt = $this->connection->prepare($insertQuery);
        if (!$insertStmt) {
            // Si hay un error preparando la consulta de inserción
            return false;
        }

        // Insertar los resultados en la tabla RatingApplicantsTest
        foreach ($results as $IdTypeTest) {
            $insertStmt->bind_param("iidi", $id_admission_application_number, $IdTypeTest, $rating, $status);
            if (!$insertStmt->execute()) {
                // Si hay un error al ejecutar la inserción
                return false;
            }
        }

        // Si todas las inserciones fueron exitosas, devolver true
        return true;
    }

    /**
     * Esta función obtiene la información de las carreras de las solicitudes a las que
     * aplicó un aspirante, los datos de los examenes que necesita cada carrera, y las notas 
     * obtenidas por solicitud en cada tipo de examen, compara dichos datos y asigna una 
     * resolución true or false a la carrera.
     *  
     *  @return boolean especifica si todo salió bien o no
     */

    public function makeResolutions()
    {
        // Extrae la solicitudes activas  de los aspirantes y las carreras a las que optó.
        $queryApplications = "SELECT A.id_admission_application_number, A.intendedprimary_undergraduate_applicant as id_undergraduate
                    FROM `Applications` A
                    WHERE
                        A.status_application = 1
                    UNION
                    SELECT A.id_admission_application_number, A.intendedsecondary_undergraduate_applicant as id_undergraduate
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
                                        id_rating_applicants_test,
                                        id_admission_application_number,
                                        intended_undergraduate_applicant,
                                        resolution_intended,
                                        status_resolution_intended_undergraduate_applicant
                                    )
                                values (?,?,?,?,?);";
        $insertStmt = $this->connection->prepare($insertQuery);
        if (!$insertStmt) {
            // Si hay un error preparando la consulta de inserción
            return false;
        }

        //Valor por defecto, indica que sus notas son validas
        $status_resolution_intended_undergraduate_applicant = 1;
        $id_rating_applicants_test = 2; //Valor por defecto para prueba eliminar luego de actualización
        // Insertar los resultados en la tabla RatingApplicantsTest
        foreach ($applications as $application) {
            $insertStmt->bind_param("iiiii", $id_rating_applicants_test, $application['id_admission_application_number'], $application['id_undergraduate'], $application['resolution_intended'], $status_resolution_intended_undergraduate_applicant);
            if (!$insertStmt->execute()) {
                // Si hay un error al ejecutar la inserción
                return false;
            }
        }
        // Si todas las inserciones fueron exitosas, devolver true
        return true;
    }

    // Método para cerrar la conexión 
    public function closeConnection()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }

}

?>