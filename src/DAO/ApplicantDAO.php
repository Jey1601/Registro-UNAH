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

require_once 'util/jwt.php';
require_once 'util/mail.php';
require_once 'AdmissionProccessDAO.php';
require_once 'util/encryption.php';
require_once 'DocumentValidationAdmissionProcessDAO.php';
require_once 'AcceptanceAdmissionProcessDAO.php';
class ApplicantDAO
{
    private $host = 'localhost';
    private $user = 'root';
    private $password = '12345';
    private $dbName = 'unah_registration';
    private $connection;
    private $id_application_inserted = null;

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
                // Abrir una instancia de finfo para detección MIME
                $finfo = finfo_open(FILEINFO_MIME_TYPE);

                // Procesar el certificado
                $imageDataCertificate = $row['certificate'];
                $fileTypeCertificate = finfo_buffer($finfo, $imageDataCertificate);

                // Procesar la identificación del solicitante
                $imageDataId = $row['image_id_applicant'];
                $fileTypeId = finfo_buffer($finfo, $imageDataId);

                // Cerrar la instancia de finfo
                finfo_close($finfo);

                // Certificado
                if (strpos($fileTypeCertificate, 'image/') === 0) {
                    // Es una imagen
                    $imageBase64Certificate = base64_encode($imageDataCertificate);
                    $fileSrcCertificate = "data:" . $fileTypeCertificate . ";base64," . $imageBase64Certificate;
                    $certificateHTML = "<img src='$fileSrcCertificate' alt='Certificate'>";
                } elseif ($fileTypeCertificate === 'application/pdf') {
                    // Es un PDF
                    $fileSrcCertificate = "data:" . $fileTypeCertificate . ";base64," . base64_encode($imageDataCertificate);
                    $certificateHTML = "<iframe src='$fileSrcCertificate' width='100%' height='600px'></iframe>";
                } else {
                    // No es un tipo soportado
                    $certificateHTML = "Archivo no soportado para el certificado.";
                }

                // ID del solicitante
                if (strpos($fileTypeId, 'image/') === 0) {
                    // Es una imagen
                    $imageBase64Id = base64_encode($imageDataId);
                    $fileSrcId = "data:" . $fileTypeId . ";base64," . $imageBase64Id;
                    $idHTML = "<img src='$fileSrcId' alt='Applicant ID'>";
                } elseif ($fileTypeId === 'application/pdf') {
                    // Es un PDF
                    $fileSrcId = "data:" . $fileTypeId . ";base64," . base64_encode($imageDataId);
                    $idHTML = "<iframe src='$fileSrcId' width='100%' height='600px'></iframe>";
                } else {
                    // No es un tipo soportado
                    $idHTML = "Archivo no soportado para la identificación.";
                }


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
                    "certificate" => $certificateHTML,
                    "idImage" => $idHTML
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



    public function createInscription($id_applicant, $first_name, $second_name, $third_name, $first_lastname, $second_lastname, $email, $phone_number, $address, $status, $id_aplicant_type, $image_id_applicant, $secondary_certificate_applicant, $id_regional_center, $regionalcenter_admissiontest_applicant, $intendedprimary_undergraduate_applicant, $intendedsecondary_undergraduate_applicant)
    {

        $mail = new mail();
        // Iniciar una transacción
        $this->connection->begin_transaction();

        try {
            // Verificamos si el aspirante ya tenía información guardada anteriormente
            if ($this->isCreated($id_applicant)) {
                // Si está creado, debemos verificar que no tenga una solicitud activa
                if ($this->hasActiveApplication($id_applicant)) {
                    // Si tiene una solicitud activa no le permitimos actualizar información ni crear una nueva
                    echo json_encode(["status" => "warning", "message" => "Usted ya está inscrito en el proceso actual"]);
                } else {
                    // Si no tiene una solicitud activa actualizamos su información
                    if (!$this->updateApplicant($id_applicant, $email, $phone_number, $address, $status)) {
                        $this->connection->rollback();
                        echo json_encode(["status" => "error", "message" => "Ha ocurrido un error en la actualización de la información"]);

                    }
                    // Creamos la nueva solicitud
                    if (!$this->createApplication($id_applicant, $id_aplicant_type, $secondary_certificate_applicant, $id_regional_center, $regionalcenter_admissiontest_applicant, $intendedprimary_undergraduate_applicant, $intendedsecondary_undergraduate_applicant)) {
                        $this->connection->rollback();
                        echo json_encode(["status" => "error", "message" => "Ha ocurrido un error al crear la solicitud"]);
                    } else {
                        $name = $first_name . " " . $second_name . " " . $third_name . " " . $first_lastname . " " . $second_lastname;
                        $mail->sendConfirmation($name, $this->id_application_inserted, $email);
                        echo json_encode([
                            "status" => "success",
                            "message" => "Inscripción creada exitosamente",
                            "id_application" => $this->id_application_inserted
                        ]);
                    }
                }
            } else {
                // Si no está creado hacemos el insert
                if (!$this->insertApplicant($id_applicant, $first_name, $second_name, $third_name, $first_lastname, $second_lastname, $email, $phone_number, $address, $image_id_applicant, $status)) {

                    $this->connection->rollback();
                    echo json_encode(["status" => "error", "message" => "Ha ocurrido un error al guardar la información del aspirante"]);
                }

                // Creamos la nueva solicitud
                if (!$this->createApplication($id_applicant, $id_aplicant_type, $secondary_certificate_applicant, $id_regional_center, $regionalcenter_admissiontest_applicant, $intendedprimary_undergraduate_applicant, $intendedsecondary_undergraduate_applicant)) {

                    $this->connection->rollback();
                    echo json_encode(["status" => "error", "message" => "Ha ocurrido un error al crear la solicitud"]);
                } else {


                    $name = $first_name . " " . $second_name . " " . $third_name . " " . $first_lastname . " " . $second_lastname;
                    $mail->sendConfirmation($name, $this->id_application_inserted, $email);
                    echo json_encode([
                        "status" => "success",
                        "message" => "Inscripción creada exitosamente",
                        "id_application" => $this->id_application_inserted
                    ]);


                }
            }

            // Si todo fue exitoso, confirmamos la transacción
            $this->connection->commit();

        } catch (Exception $e) {
            // En caso de error, revertimos la transacción
            $this->connection->rollback();
            if ($e->getCode() === 1062) {
                // Analizar el mensaje de error para personalizarlo
                if (strpos($e->getMessage(), 'Applicants.email_applicant') !== false) {
                    $message = "El correo electrónico ingresado ya está registrado. Por favor, utiliza otro.";
                } else {
                    $message = "Error de duplicación en la base de datos.";
                }

                echo json_encode([
                    "status" => "error",
                    "message" => $message
                ]);
            } else {
                // Manejo de otros errores
                echo json_encode([
                    "status" => "error",
                    "message" => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Metodo para autenticacion de un aspirante: busca al usuario, los accesos del usuario y actualiza el token en la base de datos.
     * 
     * @param string $username Username del usuario aspirante.
     * @param string $password Password del usuario aspirante.
     * 
     * @return array $response Arreglo asociativo con resultado de la autenticacion y un token (valido en caso de exito, nulo en caso de fallo).
     */
    public function authApplicant(string $username, string $password) {
        if (isset($username) && isset($password)) {
            //Busca al aspirante
            $query = "SELECT id_user_applicant, password_user_applicant FROM UsersApplicants WHERE username_user_applicant = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result(); //Obtiene resultado de la consulta a la BD

            if($result->num_rows > 0) { //Verifica que la consulta no esté vacía, si lo está es que el aspirante no está registrado
                $row = $result->fetch_array();
                $auxID = $row[0];
                $hashPassword = $row[1];
                $coincidence = Encryption::verifyPassword($password, $hashPassword);
                if($coincidence) { //La contrasena ingresada coincide con el hash registrado
                    $queryAccessArray = "SELECT `AccessControl`.id_access_control FROM `AccessControl` INNER JOIN `AccessControlRoles` ON `AccessControl`.id_access_control = `AccessControlRoles`.id_access_control INNER JOIN `Roles` ON `Roles`.id_role = `AccessControlRoles`.id_role WHERE `Roles`.id_role = 7;"; //Se buscan los accesos que tenga el usuario
                    $resultAccessArray = $this->connection->query($queryAccessArray, MYSQLI_USE_RESULT);
                    
                    $accessArray = [];
                    while ($row = $resultAccessArray->fetch_array(MYSQLI_ASSOC)) {
                        $accessArray[] = $row['id_access_control'];
                    }
                    //Liberacion de resultados de la query:
                    $resultAccessArray->free();

                    while ($this->connection->more_results() && $this->connection->next_result()) {
                        $extraResult = $this->connection->store_result();
                        if ($extraResult) {
                            $extraResult->free();
                        }
                    }
    
                    //Definicion del payload con el username y los accesos que tiene
                    $payload = [
                        'userApplicant' => $username,
                        'accessArray' => $accessArray
                    ];
                    $newToken = JWT::generateToken($payload);
                    
                    $queryUpdate = "UPDATE `TokenUserApplicant` SET token = ? WHERE id_token_user_applicant = ?;";
                    $stmtUpdate = $this->connection->prepare($queryUpdate);
                    $stmtUpdate->bind_param('si', $newToken, $auxID);
                    $resultUpdate = $stmtUpdate->execute();
    
                    if ($resultUpdate === false) { //Si la actualizacion falla
                        return $response = [
                            'success' => false,
                            'message' => 'Token no actualizado.'
                        ];
                    }
                    $stmtUpdate->close();
    
                    $response = [
                        'success' => true,
                        'message' => 'Validacion de credenciales exitosa.',
                        'token' => $newToken,
                        'typeUser' => 'applicant'
                    ];

                } else { //Contrasena incorrecta
                    return $response = [
                        'success' => false,
                        'message' => 'Credenciales invalidas.',
                        'token' => null
                    ];
                }

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
    public function getApplicantsInfoCSV()
    {
        $query = "CALL SP_APPLICANTS_DATA();";
        $applicants = $this->connection->execute_query($query);

        $csvHeaders = ["id_aspirante", "num_aplicacion", "id_examen", "tipo_examen", "nota_examen", "nombre_completo", "centro_regional"];

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
        $csvContent = stream_get_contents($csvFile);

        //Cerrar el stream
        fclose($csvFile);

        return $csvContent;
    }

    /**
     * Metodo para obtener numero de identidad, numero de solicitud, nombre del tipo de examen, nota del examen, nombre completo y centro regional de los aspirantes en un CSV.
     * 
     * @return string $applicantsAdmitted Informacion de todos aquellos  aspirantes admitidos.
     */

    public function getApplicantsAdmittedCSV()
    {
        $query = "CALL SP_APPLICANTS_ADMITTED_DATA();";
        $applicants = $this->connection->execute_query($query);

        $csvAcceptanceHeaders = ["nombre_completo_apirante_admitido", "identidad_aspirante_admitido", "direccion_aspirante_admitido", "correo_personal_aspirante_admitido", "carrera_aspirante_admitido", "centro_regional_aspirante_admitido"];

        //Crear un stream en memoria para el archivo CSV
        $csvApplicantsAdmitted = fopen('php://temp', '+r');

        //Escribir las cabeceras del CSV
        fputcsv($csvApplicantsAdmitted, $csvAcceptanceHeaders);

        //Llenado de datos del CSV
        foreach ($applicants as $applicant) {
            fputcsv($csvApplicantsAdmitted, $applicant);
        }

        //Volver al inicio del archivo para que pueda ser enviado
        rewind($csvApplicantsAdmitted);

        //Leer el contenido del archivo CSV en memoria
        $applicantsAdmitted = stream_get_contents($csvApplicantsAdmitted);

        //Cerrar el stream
        fclose($csvApplicantsAdmitted);

        return $applicantsAdmitted;
    }

    private function getResults($id_applicant)
    {
        // Asegurarse de que la conexión esté activa
        if (!$this->connection) {
            echo json_encode(['error' => 'No hay conexión a la base']);
            return;
        }

        // Primera consulta: Obtener las resoluciones de aspirante
        $queryResolutions = "
            SELECT 
                A.id_resolution_intended_undergraduate_applicant,
                F.id_notification_application_resolution,
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
            INNER JOIN `NotificationsApplicationsResolution` F ON F.id_resolution_intended_undergraduate_applicant = A.id_resolution_intended_undergraduate_applicant
            WHERE A.status_resolution_intended_undergraduate_applicant = true
            AND B.id_applicant = ?
             AND B.status_application = 1";

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
                    'id_resolution_intended_undergraduate_applicant' => $row['id_resolution_intended_undergraduate_applicant'],
                    "id_notification_application_resolution" => $row['id_notification_application_resolution'],
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
                    "id_applicant" => $row['id_applicant'],
                    "name" => $row['name'],
                    "id_admission_application_number" => $row['id_admission_application_number'],
                    "name_type_admission_tests" => $row['name_type_admission_tests'],
                    "rating_applicant" => $row['rating_applicant'],
                ];
            }
        }

        // Preparar la respuesta final
        $response = [
            "view" => 'results',
            "resolutions" => $resolutions,
            "resultsTest" => $resultsTest
        ];


        echo json_encode($response);
    }



    private function getCheckErrors($id_applicant)
    {
        // Asegurarse de que la conexión esté activa
        if (!$this->connection) {
            echo json_encode(['error' => 'No hay conexión a la base']);
            return;
        }

        // Primera consulta: Obtener las resoluciones de aspirante
        $queryData = "
            SELECT 
                Applications.id_admission_application_number,
                Applicants.id_applicant,
                CONCAT_WS(' ',
                    COALESCE(Applicants.first_name_applicant, ''),
                    COALESCE(Applicants.second_name_applicant, ''),
                    COALESCE(Applicants.third_name_applicant, '')
                ) AS name,
                CONCAT_WS(' ',
                    COALESCE(Applicants.first_lastname_applicant, ''),
                    COALESCE(Applicants.second_lastname_applicant, '')
                ) AS lastname,
                email_applicant, 
                phone_number_applicant, 
                address_applicant, 
                RegionalCenters.name_regional_center,
                first.name_undergraduate as firstC,
                second.name_undergraduate as secondC,
                Applicants.image_id_applicant,
                Applications.secondary_certificate_applicant,
                CheckApplicantApplications.id_check_applicant_applications
            FROM Applicants
            INNER JOIN Applications ON Applications.id_applicant = Applicants.id_applicant
                AND Applications.status_application=1
            INNER JOIN RegionalCenters ON Applications.idregional_center = RegionalCenters.id_regional_center
            INNER JOIN Undergraduates first ON Applications.intendedprimary_undergraduate_applicant = first.id_undergraduate
            INNER JOIN Undergraduates second ON Applications.intendedsecondary_undergraduate_applicant = second.id_undergraduate
            INNER JOIN CheckApplicantApplications ON Applicants.id_applicant = CheckApplicantApplications.id_applicant
            WHERE Applicants.id_applicant = ?
            AND CheckApplicantApplications.revision_status_check_applicant_applications = 1";

        // Preparar y ejecutar la primera consulta
        $stmt1 = $this->connection->prepare($queryData);
        if ($stmt1 === false) {
            echo json_encode(['status' => 'error', 'message' => 'Error preparando la consulta: ' . $this->connection->error]);
            return;
        }

        $stmt1->bind_param('s', $id_applicant);
        if (!$stmt1->execute()) {
            echo json_encode(['status' => 'error', 'message' => 'Error ejecutando la consulta: ' . $stmt1->error]);
            return;
        }

        $resultData = $stmt1->get_result();
        $applicantData = [];
        if ($resultData && $resultData->num_rows > 0) {
            $row = $resultData->fetch_assoc();
            $applicantData = [
                'id_applicant' => $row['id_applicant'],
                'name_applicant' => $row['name'],
                "lastname_applicant" => $row['lastname'],
                "email_applicant" => $row['email_applicant'],
                "phone_number_applicant" => $row['phone_number_applicant'],
                "address_applicant" => $row['address_applicant'],
                "name_regional_center" => $row['name_regional_center'],
                "firstC" => $row['firstC'],
                "secondC" => $row['secondC'],
                "image_id_applicant" => base64_encode($row['image_id_applicant']),
                "secondary_certificate_applicant" => base64_encode($row['secondary_certificate_applicant']),
                "id_admission_application_number" => $row['id_admission_application_number'],
                "id_check_applicant_applications" => $row['id_check_applicant_applications']
            ];
        } else {
            echo json_encode(['status' => 'warning', 'message' => 'No se encontraron datos para el solicitante.']);
            return;
        }

        // Segunda consulta: Obtener los resultados de errores
        $queryErrors = "
            SELECT 
                id_check_errors_applicant_applications, 
                incorrect_data, 
                description_incorrect_data 
            FROM CheckErrorsApplicantApplications
            WHERE id_check_applicant_applications = ?";

        $stmt2 = $this->connection->prepare($queryErrors);
        if ($stmt2 === false) {
            echo json_encode(['status' => 'error', 'message' => 'Error preparando la consulta: ' . $this->connection->error]);
            return;
        }

        $stmt2->bind_param('i', $applicantData['id_check_applicant_applications']);
        if (!$stmt2->execute()) {
            echo json_encode(['status' => 'error', 'message' => 'Error ejecutando la consulta: ' . $stmt2->error]);
            return;
        }

        $resultErrors = $stmt2->get_result();
        $resultsErrors = [];
        if ($resultErrors && $resultErrors->num_rows > 0) {
            while ($row = $resultErrors->fetch_assoc()) {
                $resultsErrors[] = [
                    "id_check_errors_applicant_applications" => $row['id_check_errors_applicant_applications'],
                    "incorrect_data" => $row['incorrect_data'],
                    "description_incorrect_data" => $row['description_incorrect_data'],
                ];
            }
        }

        // Construcción del objeto $data
        $data = [];
        foreach ($applicantData as $key => $value) {
            $readOnly = true;
            foreach ($resultsErrors as $error) {
                if ($error['incorrect_data'] === $key) {
                    $readOnly = false;
                    break;
                }
            }
            $data[$key] = [
                'value' => $value,
                'readOnly' => $readOnly
            ];
        }

        $response = [
            "status" => "success",
            "view" => 'data-edition',
            "data" => $data
        ];

        echo json_encode($response);
    }
    public function redirect($id_applicant)
    {


        try {
            $AcceptanceAdmissionProcessDAO = new AcceptanceAdmissionProcessDAO();
            $DocumentValidationAdmissionProcessDAO = new DocumentValidationAdmissionProcessDAO();

            $canEdit = $DocumentValidationAdmissionProcessDAO->getVerifyDocumentValidationAdmissionProcess();
            $canAccept = $AcceptanceAdmissionProcessDAO->getVerifyAcceptanceAdmissionProcess();



            if ($canEdit) {
                $this->getCheckErrors($id_applicant);
            } else if ($canAccept) {
                $this->getResults($id_applicant);
            } else {
                $response = [
                    "page" => 'index.html',
                    "status" => 'warning',
                    "message" => 'No tiene procesos activos.'
                ];
                echo json_encode($response);
            }
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function registerAcceptance($id_applicant_acceptance, $primaryResolution, $secondaryResolution)
    {
        date_default_timezone_set('America/Tegucigalpa');
        $currentDate = date("Y-m-d");  //Se actualiza posteriomente cuando la acepta
        $applicant_acceptance = 1; // se actualiza del formulario solo uno de los ApplicantAcceptance, la que es positiva.

        $acceptanceQuery = "UPDATE `ApplicantAcceptance`
                            SET  date_applicant_acceptance = ?,
                                applicant_acceptance = ?
                            WHERE id_applicant_acceptance = ?";

        $insertNotStmt = $this->connection->prepare($acceptanceQuery);
        $insertNotStmt->bind_param("sii", $currentDate, $applicant_acceptance, $id_applicant_acceptance);

        if (!$insertNotStmt->execute()) {
            echo json_encode(["message" => "Ha ocurrido un error guardando la decisión"]);
        }

        $resolutionQuery = " UPDATE `ResolutionIntendedUndergraduateApplicant` 
                            SET status_resolution_intended_undergraduate_applicant = 0
                            WHERE id_resolution_intended_undergraduate_applicant = ? 
                            OR id_resolution_intended_undergraduate_applicant = ?";

        $insertResolutionStmt = $this->connection->prepare($resolutionQuery);
        $insertResolutionStmt->bind_param("ii", $primaryResolution, $secondaryResolution);

        if (!$insertResolutionStmt->execute()) {
            echo json_encode(["status" => "error", "message" => "Ha ocurrido un error guardando la decisión"]);
        }



        echo json_encode(["status" => "success", "message" => "Su decisión ha sido guardada correctamente"]);
    }

    public function updateDataApplicant(
        $id_applicant,
        $first_name,
        $second_name,
        $third_name,
        $first_lastname,
        $second_lastname,
        $email,
        $phone_number,
        $address,
        $image_id_applicant,
        $secondary_certificate_applicant,
        $id_admission_application_number,
        $id_check_applicant_applications
    ) {
      
    
        // Iniciar una transacción
        $this->connection->begin_transaction();
    
        try {
            // Preparar y ejecutar la primera consulta
            $stmt1 = $this->connection->prepare(
                "UPDATE Applicants
                SET 
                    first_name_applicant = ?,
                    second_name_applicant = ?,
                    third_name_applicant = ?,
                    first_lastname_applicant = ?,
                    second_lastname_applicant = ?,
                    email_applicant = ?,
                    phone_number_applicant = ?,
                    address_applicant = ?,
                    image_id_applicant = ?
                WHERE id_applicant = ?"
            );
            $stmt1->bind_param(
                "ssssssssss",
                $first_name,
                $second_name,
                $third_name,
                $first_lastname,
                $second_lastname,
                $email,
                $phone_number,
                $address,
                $image_id_applicant,
                $id_applicant
            );
            $stmt1->execute();
    
            // Preparar y ejecutar la segunda consulta
            $stmt2 = $this->connection->prepare(
                "UPDATE Applications
                SET secondary_certificate_applicant = ?
                WHERE id_admission_application_number = ?"
            );
            $stmt2->bind_param(
                "si",
                $secondary_certificate_applicant,
                $id_admission_application_number
            );
            $stmt2->execute();
    
            // Preparar y ejecutar la tercera consulta
            $stmt3 = $this->connection->prepare(
                "UPDATE CheckApplicantApplications
                SET revision_status_check_applicant_applications = 0
                WHERE id_check_applicant_applications = ?"
            );
            $stmt3->bind_param(
                "i",
                $id_check_applicant_applications
            );
            $stmt3->execute();
    
            // Confirmar la transacción
            $this->connection->commit();
            echo json_encode(['status'=>'success', 'message'=>'Su información ha sido actualizada']);

        } catch (Exception $e) {
            // En caso de error, deshacer la transacción
            $this->connection->rollback();
            error_log("Transaction failed: " . $e->getMessage());
            
            echo json_encode(['status'=>'error', 'message'=>'Ha ocurrido un error actualizando su información']);
       
        } finally {
            // Cerrar los statements
            if (isset($stmt1)) $stmt1->close();
            if (isset($stmt2)) $stmt2->close();
            if (isset($stmt3)) $stmt3->close();
        }
    }

    // Método para insertar un nuevo aspirante
    private function insertApplicant($id_applicant, $first_name, $second_name, $third_name, $first_lastname, $second_lastname, $email, $phone_number, $address, $image_id_applicant, $status)
    {

        // Preparar la consulta SQL de inserción
        $query = "INSERT INTO Applicants (id_applicant, first_name_applicant, second_name_applicant, third_name_applicant, first_lastname_applicant, second_lastname_applicant, email_applicant, phone_number_applicant, address_applicant, image_id_applicant, status_applicant) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)";

        // Prepared statement para evitar SQL injection
        if ($stmt = $this->connection->prepare($query)) {
            // Vinculamos los parámetros a la consulta
            $stmt->bind_param("ssssssssssi", $id_applicant, $first_name, $second_name, $third_name, $first_lastname, $second_lastname, $email, $phone_number, $address, $image_id_applicant, $status);

            // Ejecutamos la consulta
            if ($stmt->execute()) {
                // Commit explícito de la transacción actual
                $this->connection->commit();
                $stmt->close();
                $this->createUserApplicant($id_applicant);
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
            echo json_encode(["status" => "error", "message" => "Error en la preparación de la consulta updateApplicant: " . $this->connection->error]);
        }

    }

    private function createApplication($id_applicant, $id_aplicant_type, $secondary_certificate_applicant, $id_regional_center, $regionalcenter_admissiontest_applicant, $intendedprimary_undergraduate_applicant, $intendedsecondary_undergraduate_applicant)
    {   //Nueva instancia de admision proccess
        $AdmissionProccessDAO = new AdmissionProccessDAO();
        // Extraer el proceso de admisión activo
        // $id_admission_process = $this->getAdmissionProcess();
        $id_admission_process = $AdmissionProccessDAO->getAdmissionProcess()['id_admission_process'];
        if (!$id_admission_process) {
            echo json_encode(["status" => "warning", "message" => "No hay un proceso de admisión activo." . $this->connection->error]);

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
                $this->id_application_inserted = $id_application;
                //Se crea el usuario del aspirante relacionado con la solicitud recien creada y los rating test
                if ($this->createUserApplicant($id_applicant) && $this->createRatingApplicantsTest($id_application)) {

                    $stmt->close();
                    return true; // Éxito
                } else {
                    echo json_encode(["status" => "error", "message" => "Error en la creación de la solicitud: " . $stmt->error]);
                }
                ;


            } else {
                // Registrar error en la ejecución
                echo json_encode(["status" => "error", "message" => "Error en la creación de la solicitud: " . $stmt->error]);

                $stmt->close();
                return false;
            }
        } else {
            // Registrar error en la preparación

            echo json_encode(["status" => "error", "message" => "Error en la preparación de la consulta createApplication: " . $this->connection->error]);
            return false;
        }
    }

    private function createUserApplicant($id_applicant)
    {
        $generated_password = Password::generatePassword();
        $password_user_applicant = Encryption::hashPassword($generated_password);
        $status_user_applicant = 1;
        // Consulta de inserción
        $query = "INSERT INTO UsersApplicants (username_user_applicant,password_user_applicant,status_user_applicant)  VALUES (?, ?, ?)";

        // Preparar la consulta
        if ($stmt = $this->connection->prepare($query)) {
            // Vincular parámetros
            $stmt->bind_param(
                "ssi",
                $id_applicant,
                $password_user_applicant,
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
    /**
     * Esta función verifica otbtiene el proceso de admisión activo
     * 
     *  @return boolean false si no tiene ninguna información en la base de datos
     */
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

    /**
     * Esta función verifica si un aspirante ya cuenta con información en la
     * base de datos, es decir que ya tiene un perfil creado. 
     *  @param int $id_applicant número de identificación del aspirante
     *  @return boolean false si no tiene ninguna información en la base de datos
     */
    private function isCreated($id_applicant)
    {
        $query = "SELECT id_applicant FROM Applicants WHERE id_applicant = ?";

        if ($stmt = $this->connection->prepare($query)) {

            $stmt->bind_param("s", $id_applicant);


            if ($stmt->execute()) {

                $stmt->store_result();


                if ($stmt->num_rows > 0) {
                    $stmt->close();
                    return true; // El registro existe
                } else {
                    $stmt->close();
                    return false; // El registro no existe
                }
            } else {


                $stmt->close();
                return false;
            }
        } else {



            return false;
        }
    }

    /**
     * Esta función verifica si un aspirante tiene una solicitud activa en los
     * registros.
     *  @param int $id_applicant número de identificación del aspirante
     *  @return boolean false si no tiene ninguna solicitud activa
     */

    private function hasActiveApplication($id_applicant)
    {
        $query = "SELECT 1 FROM Applications WHERE id_applicant = ? AND status_application = 1";


        if ($stmt = $this->connection->prepare($query)) {

            $stmt->bind_param("s", $id_applicant);


            if ($stmt->execute()) {

                $stmt->store_result();


                if ($stmt->num_rows > 0) {
                    $stmt->close();
                    return true; // Existe una aplicación activa
                } else {
                    $stmt->close();
                    return false; // No hay aplicaciones activas
                }
            } else {

                $stmt->close();


                return false;
            }
        } else {



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


    // Método para cerrar la conexión 
    public function closeConnection()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }

}

?>