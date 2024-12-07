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
include_once "AdmissionAdminDAO.php";
require_once 'util/encryption.php';
require_once 'DocumentValidationAdmissionProcessDAO.php';
require_once 'AcceptanceAdmissionProcessDAO.php';
require_once 'AdmissionAdminDAO.php';
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

    

    public function getDataApplicant ($idApplicant){
        try {
            if (!is_string($idApplicant)) {
                throw new InvalidArgumentException("No se ha ingresado el parámetro correcto, debe ser un VARCHAR(20).");
            }            
            $applicantCheckPending = $this->connection->execute_query("CALL APPLICANT_DATA_VIEW('$idApplicant')");

            if ($applicantCheckPending) { 
                    $dataApplicantCheckPending = $applicantCheckPending->fetch_assoc();
                    if($dataApplicantCheckPending != null){
                        return [
                            "status" => "success",
                            "applicantCheckDataPending" => $dataApplicantCheckPending
                        ];
                    }else{
                        return [
                            "status" => "error",
                            "message" => "Solicitud ya procesada "
                        ];
                    }
                 
            } else {
                return [
                    "status" => "error",
                    "message" => "Error en el procedimiento APPLICANT_DATA_VIEW(): " . $this->connection->error
                ];
            }
        } catch (Exception $exception) {
            return [
                "status" => "error",
                "message" => "Excepción en getDataApplicant() capturada: " . $exception->getMessage(),
                "code" => $exception->getCode()
            ];
        }
    }

    public function getPendingCheckData ($userNameAdmin){
        $admissionAdmin = new AdmissionAdminDAO();
        $dataIdUser = $admissionAdmin->getUserAdminId($userNameAdmin); //obtener el id del usuario administrador
        if($dataIdUser['status']=='success'){
            $idUser = $dataIdUser['IdUserAdmissionAdministrator'];
            $applicantsCheckPending = $admissionAdmin->getPendingCheckApplicant($idUser); // primero obtengo los id de los aspirantes asignados al usuario administrador.
            if ($applicantsCheckPending['status'] !== 'success') {
                return json_encode(["error" => "No se ha encontrado aspirantes asignados al usuario administrador"]);
            }
            if ($applicantsCheckPending['status'] == 'success') {
                $applicationsData = [];
                $finfo = finfo_open(FILEINFO_MIME_TYPE); // Abrir una instancia de finfo para detección MIME
                foreach ($applicantsCheckPending['AllApplicantsCheckPending'] as $pendingApplicant) {
                    $idApplicantCheck = $pendingApplicant['id_applicant'];
                    $responseGetData = $this->getDataApplicant ($idApplicantCheck);
                    if ($responseGetData['status']== 'success') {
                            $dataApplicant = $responseGetData['applicantCheckDataPending'];
                            // Procesar el certificado
                            $imageDataCertificate = $dataApplicant['certificate'];
                            $fileTypeCertificate = finfo_buffer($finfo, $imageDataCertificate);
            
                            // Procesar la identificación del solicitante
                            $imageDataId = $dataApplicant['image_id_applicant'];
                            $fileTypeId = finfo_buffer($finfo, $imageDataId);
            
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
                                $idHTML = "Archivo no soportado para la identificacion.";
                            }
            
            
                            // Crear un arreglo asociativo con claves más descriptivas
                            $application = [
                                "id_applicant" => $dataApplicant['id_applicant'],
                                "name" => $dataApplicant['name'],
                                "lastname" => $dataApplicant['lastname'],
                                "phone_number_applicant" => $dataApplicant['phone_number_applicant'],
                                "address_applicant" => $dataApplicant['address_applicant'],
                                "email_applicant" => $dataApplicant['email_applicant'],
                                "id_admission_application_number" => $dataApplicant['id_admission_application_number'],
                                "name_admission_process" => $dataApplicant['name_admission_process'],
                                "name_regional_center" => $dataApplicant['name_regional_center'],
                                "firstC" => $dataApplicant['firstC'],
                                "secondC" => $dataApplicant['secondC'],
                                "certificate" => $certificateHTML,
                                "idImage" =>$idHTML,
                                "id_check_applicant_applications" => $dataApplicant['id_check_applicant_applications']
                            ];
                            $applicationsData[] = $application;
                    }
                }
                finfo_close($finfo); 
            }
            echo json_encode($applicationsData);

        }
    }

    /**
     * Obtiene la información de las solicitudes activas junto con la información del aplicante. 
     * No disierne entre los usuarios con rol de verificación de documentos, es decir, muestra 
     * todas las solitiudes.
     * 
     * Este método llama a un procedimiento almacenado que devuelve la lista  de todas las solicitudes
     * activas juntos con la información del aplicante, recorre los resultados y convierte los archivos
     * de imagen en codigo html para su inserción directa en el frontend. Convierte las imágenes o PDFs 
     * en datos base64 para ser presentados en HTML.
     * 
     * @return void  Devuelve los datos procesados de los aspirantes en formato JSON.
     */
    public function viewData(){
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
                    $certificateHTML = "Archivo no soportado para el certificado";
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
                    $idHTML = "Archivo no soportado para la identificacion";
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
                    "idImage" => $idHTML,
                    "id_check_applicant_applications" => $row['id_check_applicant_applications']
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


       /**
     * Verifica si un solicitante ha excedido el número permitido de aplicaciones.
     *
     * Este método realiza una llamada a un procedimiento almacenado que devuelve 
     * el número de aplicaciones de un solicitante con múltiples calificaciones. 
     * Si el número excede 3, devuelve un mensaje de error. De lo contrario, 
     * devuelve un estado de éxito.
     *
     *  @param string $id_applicant El identificador único del solicitante. Debe ser una cadena.
     * 
     * @return array Retorna un arreglo asociativo con los siguientes valores:
     *               - `status` (string): "success" o "error".
     *               - `message` (string, opcional): Mensaje de error si ocurre un problema.
     *               - `code` (int, opcional): Código de error si ocurre una excepción.
     *
     * @throws InvalidArgumentException Si el parámetro `$id_applicant` no es una cadena.
     */
    public function limitOfApplications($id_applicant){
        try {
            if (!is_string($id_applicant)) {
                throw new InvalidArgumentException("No se han ingresado los parámetros correctos en limitOfApplications().");
            }            
            $this->connection->execute_query("CALL GET_APPLICATIONS_WITH_MULTIPLE_RATINGS('$id_applicant', @result);");
            $numerApplications = $this->connection->execute_query("SELECT @result AS total_applications;");
            $numerApplications =  $numerApplications->fetch_assoc();
            if ($numerApplications) { 
                $totalApplications = $numerApplications['total_applications'] ?? null;
                if ($totalApplications === null) {
                    return [
                        "status" => "error",
                        "message" => "No se pudo obtener el número de aplicaciones."
                    ];
                }
                if ( $totalApplications > 3) { 
                    return [
                        "status" => "error",
                        "message" => "Ha sobrepasado el numero de aplicaciones permitidas."
                    ];
                } else {
                    return [
                        "status" => "success"
                    ];
                }
            } else {
                return [
                    "status" => "error",
                    "message" => "Error en el procedimiento GET_APPLICATIONS_WITH_MULTIPLE_RATINGS(): " . $this->connection->error
                ];
            }
        } catch (Exception $exception) {
            return [
                "status" => "error",
                "message" => "Excepción en limitOfApplications() capturada: " . $exception->getMessage(),
                "code" => $exception->getCode()
            ];
        }
    }

    /**
     * Crea una nueva inscripción para un aspirante.
     * 
     * Este método realiza la inscripción de un aspirante en el sistema. Incluye la verificación
     * de si el aspirante ya existe, la actualización de información en caso necesario, y la 
     * creación de una nueva solicitud. Además, envía un correo de confirmación al aspirante.
     * 
     * Se utiliza una transacción para garantizar la integridad de los datos en caso de fallos.
     * 
     * @param string $id_applicant ID único del aspirante.
     * @param string $first_name Primer nombre del aspirante.
     * @param string|null $second_name Segundo nombre del aspirante 
     * @param string|null $third_name Tercer nombre del aspirante 
     * @param string $first_lastname Primer apellido del aspirante.
     * @param string|null $second_lastname Segundo apellido del aspirante 
     * @param string $email Dirección de correo electrónico del aspirante.
     * @param string $phone_number Número de teléfono del aspirante.
     * @param string $address Dirección física del aspirante.
     * @param string $status Estado actual del aspirante 
     * @param int $id_aplicant_type Tipo de aspirante (por ejemplo, primer ingreso).
     * @param string $image_id_applicant Imagen de identificación del aspirante (en binario).
     * @param string $secondary_certificate_applicant Certificado de estudios secundarios del aspirante (en binario).
     * @param int $id_regional_center ID del centro regional al que aplica el aspirante.
     * @param string $regionalcenter_admissiontest_applicant Detalles del centro regional para el examen de admisión.
     * @param string $intendedprimary_undergraduate_applicant Carrera principal deseada por el aspirante.
     * @param string|null $intendedsecondary_undergraduate_applicant Carrera secundaria deseada 
     * 
     * @return void Retorna los resultados en formato JSON.
     * - `status`: Indica el estado de la operación (`success`, `warning`, `error`).
     * - `message`: Proporciona información sobre el resultado.
     * - `id_application`: ID de la solicitud creada (en caso de éxito).
     */
    public function createInscription($id_applicant, $first_name, $second_name, $third_name, $first_lastname, $second_lastname, $email, $phone_number, $address, $status, $id_aplicant_type, $image_id_applicant, $secondary_certificate_applicant, $id_regional_center, $regionalcenter_admissiontest_applicant, $intendedprimary_undergraduate_applicant, $intendedsecondary_undergraduate_applicant)
    {   
        //Configuramos la nueva contraseña:
        $generated_password = Password::generatePassword();
    //    $password_user_applicant = Encryption::hashPassword($generated_password);

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
                    if (!$this->createApplication($id_applicant, $id_aplicant_type, $secondary_certificate_applicant, $id_regional_center, $regionalcenter_admissiontest_applicant, $intendedprimary_undergraduate_applicant, $intendedsecondary_undergraduate_applicant,  $generated_password )) {
                        $this->connection->rollback();
                        echo json_encode(["status" => "error", "message" => "Ha ocurrido un error al crear la solicitud"]);
                    } else {
                        $name = $first_name . " " . $second_name . " " . $third_name . " " . $first_lastname . " " . $second_lastname;
                        $mail->sendConfirmation($name, $this->id_application_inserted, $email,  $generated_password);
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
                if (!$this->createApplication($id_applicant, $id_aplicant_type, $secondary_certificate_applicant, $id_regional_center, $regionalcenter_admissiontest_applicant, $intendedprimary_undergraduate_applicant, $intendedsecondary_undergraduate_applicant,  $generated_password )) {

                    $this->connection->rollback();
                    echo json_encode(["status" => "error", "message" => "Ha ocurrido un error al crear la solicitud"]);
                } else {


                    $name = $first_name . " " . $second_name . " " . $third_name . " " . $first_lastname . " " . $second_lastname;
                    $mail->sendConfirmation($name, $this->id_application_inserted, $email,  $generated_password);
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

            if($result->num_rows > 0) { //Verifica que la consulta no esté vacía, si lo está es que el usuario no está registrado
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $auxID = $row['id_user_applicant'];
                $hashPassword = $row['password_user_applicant'];
                $coincidence = Encryption::verifyPassword($password, $hashPassword);
                if($coincidence) { //La contrasena ingresada coincide con el hash registrado
                    $queryAccessArray = "SELECT `AccessControl`.id_access_control FROM `AccessControl` INNER JOIN `AccessControlRoles` ON `AccessControl`.id_access_control = `AccessControlRoles`.id_access_control INNER JOIN `Roles` ON `Roles`.id_role = `AccessControlRoles`.id_role WHERE `Roles`.id_role = 12;";
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

                    $queryCheck = "SELECT id_user_applicant FROM TokenUserApplicant WHERE id_user_applicant = ?;";
                    $stmtCheck = $this->connection->prepare($queryCheck);
                    $stmtCheck->bind_param('i', $auxID);
                    $stmtCheck->execute();
                    $stmtCheck->store_result();
                    
                    // Si ya existe el registro, se actualiza, si no, se inserta
                    if ($stmtCheck->num_rows > 0) {
                        // Si existe, actualizamos el token
                        $queryUpdate = "UPDATE `TokenUserApplicant` SET token = ? WHERE id_user_applicant = ?;";
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
                    } else {
                        // Si no existe, insertamos un nuevo registro
                        $queryInsert = "INSERT INTO `TokenUserApplicant` (id_user_applicant, token) VALUES (?, ?);";
                        $stmtInsert = $this->connection->prepare($queryInsert);
                        $stmtInsert->bind_param('is', $auxID, $newToken);
                        $resultInsert = $stmtInsert->execute();

                        if ($resultInsert === false) { //Si la insercion falla
                            return $response = [
                                'success' => false,
                                'message' => 'Token no registrado.'
                            ];
                        }
                    }
    
                  
    
                    $response = [
                        'success' => true,
                        'message' => 'Validacion de credenciales exitosa.',
                        'token' => $newToken,
                        'typeUser' => 'applicant'
                    ];

                } else { //Contrasena incorrecta
                    return $response = [
                        'success' => false,
                        'message' => 'Contrasena no coincide.',
                        'token' => null
                    ];
                }

            } else { //Usuario no registrado
                $response = [
                    'success' => false,
                    'message' => 'Usuario y/o numero de solicitud no encontrados.',
                    'token' => null
                ];
            }

        } else { //Username y/o password nulos
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
            if (!empty(array_filter($applicant))) { // Verifica que la línea no esté vacía
                fputcsv($csvFile, $applicant);
            }
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

        $csvAcceptanceHeaders = ["nombre_completo_apirante_admitido", "identidad_aspirante_admitido", "direccion_aspirante_admitido", "celular_aspirante_admitido","correo_personal_aspirante_admitido", "carrera_aspirante_admitido", "centro_regional_aspirante_admitido"];

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

    /**
     * Obtiene los resultados de resoluciones y calificaciones de exámenes para un aspirante.
     * 
     * Este método consulta la base de datos para obtener:
     * - Resoluciones asociadas a un aspirante, incluyendo detalles de la carrera y centro regional.
     * - Resultados de los exámenes de admisión, junto con la información del aspirante.
     * 
     * Combina los datos obtenidos en una estructura JSON para devolverlos como respuesta.
     * 
     * @param string $id_applicant El ID único del aspirante cuyos resultados se desean obtener.
     * 
     * @return void Retorna un JSON con la estructura:
     * - `view`: Indica la vista asociada, en este caso `"results"`.
     * - `resolutions`: Array con los datos de resoluciones del aspirante:
     *   - `id_resolution_intended_undergraduate_applicant`: ID de la resolución.
     *   - `id_notification_application_resolution`: ID de la notificación de resolución.
     *   - `id_applicant`: ID del aspirante.
     *   - `id_undergraduate`: ID de la carrera asociada.
     *   - `name_undergraduate`: Nombre de la carrera asociada.
     *   - `resolution_intended`: Detalles de la resolución.
     *   - `name_regional_center`: Nombre del centro regional.
     *   - `resultsTest`: Array con los datos de calificaciones de exámenes del aspirante:
     *   - `id_applicant`: ID del aspirante.
     *   - `name`: Nombre completo del aspirante.
     *   - `id_admission_application_number`: Número de solicitud de admisión.
     *   - `name_type_admission_tests`: Nombre del tipo de examen.
     *   - `rating_applicant`: Calificación obtenida.
     * 
     * @throws Exception Si ocurre un error en las consultas o al preparar los resultados.
     */
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

    /**
     * Obtiene los datos del aspirante y los errores de validación detectados en su aplicación.
     * 
     * Este método realiza dos consultas:
     * 1. Recupera la información del aspirante junto con detalles de su solicitud de admisión.
     * 2. Obtiene los errores identificados durante la revisión de la aplicación del aspirante.
     * 
     * Combina estos datos en un objeto JSON para devolverlos como respuesta, con atributos marcados como editables si presentan errores.
     * 
     * @param string $id_applicant El ID único del aspirante cuyos datos y errores se desean recuperar.
     * 
     * @return void Retorna un JSON con la estructura:
     * - `status`: Indica el estado del proceso (`success`, `warning` o `error`).
     * - `view`: Nombre de la vista asociada, en este caso `"data-edition"`.
     * - `data`: Datos del aspirante en formato clave-valor, con información sobre si cada campo es editable.
     * - `value`: Valor del dato.
     * - `readOnly`: `true` si el dato es de solo lectura, `false` si puede ser editado debido a un error asociado.
     * 
     * @throws Exception Si ocurre un error en las consultas o al procesar los datos.
     */
    private function getCheckErrors($id_applicant)
    {
        // Asegurarse de que la conexión esté activa
        if (!$this->connection) {
            echo json_encode(['error' => 'No hay conexión a la base']);
            return;
        }

        // Primera consulta: Obtener los datos del aspirante de aspirante
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
            echo json_encode(['status' => 'warning', 'message' => 'No se encontraron datos para el solicitante. No tiene acciones por hacer actualmente.', 'view' =>'data-edition']);
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

    /**
     * Redirige al aspirante al proceso correspondiente basado en el estado actual de su solicitud.
     * 
     * Este método determina el flujo de trabajo del aspirante verificando si:
     * 1. Puede editar su información debido a errores detectados en la validación de documentos.
     * 2. Puede aceptar o revisar los resultados de su proceso de admisión.
     * 
     * Según el estado:
     * - Llama al método `getCheckErrors` si el aspirante debe corregir datos.
     * - Llama al método `getResults` si el aspirante puede visualizar resultados.
     * - Retorna un mensaje de advertencia si no hay procesos activos.
     * 
     * @param string $id_applicant El ID único del aspirante cuyo flujo de trabajo se debe determinar.
     * 
     * @return void Retorna un JSON con la estructura:
     * - Si el aspirante debe corregir datos:
     *  - Llama a `getCheckErrors`, que genera una respuesta JSON con los datos y errores editables.
     * - Si el aspirante puede aceptar resultados:
     *  - Llama a `getResults`, que genera una respuesta JSON con los resultados del proceso.
     * - Si no hay procesos activos:
     *  - `page`: Indica la página a la que se debe redirigir (`index.html`).
     *  - `status`: `warning`.
     *  - `message`: Mensaje explicativo.
     * -  Si ocurre un error:
     *  - `status`: `error`.
     *  - `message`: Detalle del error ocurrido.
     * 
     * @throws Exception Captura cualquier excepción generada durante el flujo y la incluye en la respuesta JSON.
     */

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
    /**
     * Registra la aceptación de una resolución de aspirante y actualiza los estados correspondientes.
     * 
     * Este método realiza las siguientes acciones:
     * 1. Registra la decisión del aspirante en la tabla `ApplicantAcceptance`.
     * 2. Actualiza el estado de las resoluciones relacionadas en la tabla `ResolutionIntendedUndergraduateApplicant` para desactivarlas.
     * 
     * @param int $id_applicant_acceptance ID único de la aceptación del aspirante que se actualiza.
     * @param int $primaryResolution ID de la resolución primaria que se debe desactivar.
     * @param int $secondaryResolution ID de la resolución secundaria que se debe desactivar.
     * 
     * @return void Devuelve un JSON con la estructura:
     * - Si todo es exitoso:
     *   - `status`: `success`.
     *   - `message`: "Su decisión ha sido guardada correctamente".
     * - Si ocurre un error al guardar la aceptación:
     *   - `message`: "Ha ocurrido un error guardando la decisión".
     * - Si ocurre un error al actualizar las resoluciones:
     *   - `status`: `error`.
     *   - `message`: "Ha ocurrido un error guardando la decisión".
     * 
     * @throws Exception Este método no captura explícitamente excepciones, pero errores de conexión o ejecución del statement pueden lanzarlas.
     */

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
    /**
     * Actualiza los datos de un aspirante en las tablas `Applicants`, `Applications` y `CheckApplicantApplications`.
     * 
     * Es utizado durante el proceso de correción de datos, se actualiza información de la aplicación también.
     * 
     * Este método realiza las siguientes acciones:
     * 1. Actualiza los datos personales del aspirante en la tabla `Applicants`.
     * 2. Actualiza el certificado secundario del aspirante en la tabla `Applications`.
     * 3. Cambia el estado de revisión en la tabla `CheckApplicantApplications`.
     * 4. Utiliza una transacción para garantizar la integridad de los datos.
     * 
     * @param string $id_applicant ID único del aspirante que será actualizado.
     * @param string $first_name Primer nombre del aspirante.
     * @param string $second_name Segundo nombre del aspirante.
     * @param string $third_name Tercer nombre del aspirante.
     * @param string $first_lastname Primer apellido del aspirante.
     * @param string $second_lastname Segundo apellido del aspirante.
     * @param string $email Correo electrónico del aspirante.
     * @param string $phone_number Número de teléfono del aspirante.
     * @param string $address Dirección del aspirante.
     * @param string $image_id_applicant Imagen del documento de identificación del aspirante (binario).
     * @param string $secondary_certificate_applicant Certificado secundario del aspirante (binario).
     * @param int $id_admission_application_number Número de solicitud de admisión asociado.
     * @param int $id_check_applicant_applications ID único de la revisión del aspirante.
     * 
     * @return void Devuelve un JSON con la estructura:
     * - Si todo es exitoso:
     *   - `status`: `success`.
     *   - `message`: "Su información ha sido actualizada".
     * - Si ocurre un error durante la transacción:
     *   - `status`: `error`.
     *   - `message`: "Ha ocurrido un error actualizando su información".
     * 
     * @throws Exception Este método utiliza transacciones y captura errores para deshacer los cambios en caso de falla.
     */
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

    /**
     * Inserta un nuevo registro de aspirante en la tabla `Applicants`.
     * 
     * 
     * @param string $id_applicant ID único del aspirante.
     * @param string $first_name Primer nombre del aspirante.
     * @param string|null $second_name Segundo nombre del aspirante 
     * @param string|null $third_name Tercer nombre del aspirante 
     * @param string $first_lastname Primer apellido del aspirante.
     * @param string|null $second_lastname Segundo apellido del aspirante 
     * @param string $email Correo electrónico del aspirante.
     * @param string $phone_number Número de teléfono del aspirante.
     * @param string $address Dirección del aspirante.
     * @param string|null $image_id_applicant Imagen de la identificación del aspirante (binario)
     * @param int $status Estado del aspirante (por ejemplo, 1 para activo, 0 para inactivo).
     * 
     * @return bool Retorna `true` si la inserción es exitosa; de lo contrario, retorna `false`.
     * 
     * @throws Exception Este método maneja transacciones y puede lanzar excepciones en caso de errores no controlados.
     */
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

    /**
     * Actualiza los datos básicos de un aspirante en la tabla `Applicants`.
     * 
     * Es utilizado durante el proceso de inscripción.
     * 
     * @param string $id_applicant ID único del aspirante que se va a actualizar.
     * @param string $email Nuevo correo electrónico del aspirante.
     * @param string $phone_number Nuevo número de teléfono del aspirante.
     * @param string $address Nueva dirección del aspirante.
     * @param int $status Nuevo estado del aspirante (1 para activo, 0 para inactivo, etc.).
     * 
     * @return bool Retorna `true` si la actualización es exitosa; de lo contrario, retorna `false`.
     * 
     * @throws Exception Este método maneja errores relacionados con la preparación y ejecución de la consulta.
     */

    private function updateApplicant($id_applicant, $email, $phone_number, $address, $status){
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
            return false;
        }

    }

    /**
     * Crea una nueva solicitud de admisión para un aspirante.
     *
     * Este método inserta una nueva solicitud de admisión en la base de datos, asociada a un proceso de admisión activo, y
     * crea un usuario para el aspirante. Además, se encargará de gestionar el tipo de aspirante y los datos relacionados.
     *
     * @param string $id_applicant El ID del aspirante.
     * @param int $id_aplicant_type El tipo de aspirante 
     * @param string $secondary_certificate_applicant El certificado de secundaria del aspirante.
     * @param int $id_regional_center El ID del centro regional del aspirante.
     * @param int $regionalcenter_admissiontest_applicant El ID del centro regional donde el aspirante realizará el examen.
     * @param int $intendedprimary_undergraduate_applicant La carrera de pregrado primaria deseada por el aspirante.
     * @param int $intendedsecondary_undergraduate_applicant La carrera de pregrado secundaria deseada por el aspirante.
     * @param string $password_user_applicant La contraseña para la creación del usuario del aspirante.
     *
     * @return bool Devuelve true si la solicitud se crea correctamente, false si ocurre un error.
     *
     * @throws Exception Si no hay un proceso de admisión activo o si ocurre algún error en la creación de la solicitud.
     */
    private function createApplication($id_applicant, $id_aplicant_type, $secondary_certificate_applicant, $id_regional_center, $regionalcenter_admissiontest_applicant, $intendedprimary_undergraduate_applicant, $intendedsecondary_undergraduate_applicant,  $password_user_applicant )
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
                if ($this->createUserApplicant($id_applicant,  $password_user_applicant ) && $this->createRatingApplicantsTest($id_application, )) {

                    $stmt->close();
                    return true; // Éxito
                } else {
                    echo json_encode(["status" => "error", "message" => "Error en la creación de la solicitud: " . $stmt->error]);
                    return false;
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

     /**
     * Crea un usuario para el aspirante en el sistema.
     *
     * Este método inserta un nuevo usuario en la tabla `UsersApplicants`, asociando al aspirante un nombre de usuario, 
     * una contraseña cifrada, y un estado de usuario activo.
     *
     * @param string $id_applicant El ID del aspirante
     * @param string $password_user_applicant La contraseña del aspirante que será cifrada antes de ser almacenada.
     *
     * @return bool Devuelve true si el usuario se crea correctamente, false si ocurre un error.
     *
     * @throws Exception Si ocurre un error durante la preparación o ejecución de la consulta SQL.
     */
    
    private function createUserApplicant($id_applicant, $password_user_applicant)
    {
      
        $status_user_applicant = 1;
        $hashPassword = Encryption::hashPassword($password_user_applicant);
        // Consulta de inserción
        $query = "INSERT INTO UsersApplicants (username_user_applicant,password_user_applicant,status_user_applicant)  VALUES (?, ?, ?)";

        // Preparar la consulta
        if ($stmt = $this->connection->prepare($query)) {
            // Vincular parámetros
            $stmt->bind_param(
                "ssi",
                $id_applicant,
                $hashPassword,
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

            //$stmt->close();
            return false;
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
    private function createRatingApplicantsTest($id_admission_application_number){
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
      * Obtiene el CheckApplicantApplications que se encuentra activo y pertenece a un aplicante.
      */
    public function getCheck($idApplicant, $idAplication){
        try {
            if (!is_string($idApplicant) && !is_int($idAplication)) {
                throw new InvalidArgumentException("No se han ingresado los parámetros correctos.");
            }            
            $IdCheck = $this->connection->execute_query("CALL GET_CHECK_BY_IDAPPLICANT_IDAPLICATION($idApplicant,$idAplication)");

            if ($IdCheck) { 
                if ( $IdCheck->num_rows == 1) { 
                    $IdCheckActivo=  $IdCheck->fetch_assoc();
                    return [
                        "status" => "success",
                        "id_check_applicant_applications" => $IdCheckActivo['id_check_applicant_applications']
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
                    "message" => "Error en el procedimiento GET_CHECK_BY_IDAPPLICANT_IDAPLICATION(): " . $this->connection->error
                ];
            }
        } catch (Exception $exception) {
            return [
                "status" => "error",
                "message" => "Excepción en getCheck() capturada: " . $exception->getMessage(),
                "code" => $exception->getCode()
            ];
        }
    }

    /**
     * Funcion que inserta los Check Erros identificados por el usuario administrador.
     * 
     * @param int $idCheckApplicant Identificador del CheckApplicantApplications, al que pertenece el CheckError
     * @param string $wrongData Nombre del campo en el que se encuentra el CheckError
     * @param string $description Descripcion General Ingresada por el usuario. Puede ser Null.
     * @return array Resultado del proceso, incluyendo estado y mensaje.
     *
     * @throws InvalidArgumentException Si los parámetros proporcionados no son validos.
     */
    public function insertCheckErrors($idCheckApplicant, $wrongData, $description){
        try {
            if (!is_string($wrongData) && !is_int($idCheckApplicant)) {
                throw new InvalidArgumentException("No se han ingresado los parámetros correctos.");
            }            
            $this->connection->execute_query("CALL INSERT_CHECK_ERROR($idCheckApplicant, '$wrongData', '$description')");
            $this->connection->commit();
            return [
                "status" => "success",
                "message" => "Registro insertado correctamente."
            ];
        } catch (Exception $exception) {
            $this->connection->rollback();
            return [
                "status" => "error",
                "message" => "Excepción en insertCheckErrors() capturada: " . $exception->getMessage(),
                "code" => $exception->getCode()
            ];
        }
    }

    /**
     * Funcion que elimina el estado de activo de todos los errores reportados deacuerdo en el identificador del CheckApplicantApplications. 
     * 
     * @param int $idCheckApplicant Id del CheckApplicantApplications al que pertenece el error reportado.
     * 
     * @return array Se retorna el estado y mensaje del resultado del proceso.
     */
    public function deleteCheckErrors($idCheckApplicant){
        try {
            if (!is_int($idCheckApplicant)) {
                throw new InvalidArgumentException("No se ha ingreso el parametros correcto.");
            }            
            $this->connection->execute_query("CALL DELETE_CHECK_ERRORS_BY_APPLICANT($idCheckApplicant)");
            $this->connection->commit();
            return [
                "status" => "success",
                "message" => "Errores de la informacion del aspirante y su aplicacion, ya no estan activos."
            ];
        } catch (Exception $exception) {
            $this->connection->rollback();
            return [
                "status" => "error",
                "message" => "Excepción en deleteCheckErrors() capturada: " . $exception->getMessage(),
                "code" => $exception->getCode()
            ];
        }
    }
    
    /**
     * Realiza la validación de errores para un solicitante y maneja las operaciones de inserción o eliminación de errores.
     *
     * Dependiendo del estado de verificación, la función eliminará los errores asociados al solicitante o insertará nuevos errores en la base de datos.
     *
     * @param int $idCheckApplicant El ID del solicitante cuyo estado se va a verificar.
     * @param int $verificationStatus El estado de la verificación (1 para eliminar los errores, cualquier otro valor para insertarlos).
     * @param array $errorData Los datos de error a insertar si el estado de verificación no es 1.
     *
     * @return array Un array con el estado y mensaje de la operación. Si ocurre un error, también se incluye un código de error.
     *
     * @throws Exception Si ocurre un error al intentar eliminar o insertar los errores.
     */
    public function validationCheckError($idCheckApplicant,$verificationStatus,$errorData){
        if($verificationStatus==1){ 
            try {
                $this->deleteCheckErrors($idCheckApplicant);
                return [
                    "status" => "success",
                    "message" => "Campos Check Error eliminados correctamente" 
                ];
            } catch (Exception $exception) {
                return [
                    "status" => "error",
                    "message" => "Fallo al eliminar los errores de la información del aspirante: " . $exception->getMessage(),
                    "code" => $exception->getCode()
                ];
            }
        }else{
            try {
                foreach($errorData as $errorCampo){
                    $descriptionCampo = "Hemos encontrado informacion que no cumple con nuestros parametros ".$errorCampo;
                    $this->insertCheckErrors($idCheckApplicant, $errorCampo, $descriptionCampo);
                }
                return [
                    "status" => "success",
                    "message" => "Campos Check Error creados correctamente" 
                ];
            } catch (Exception $exception) {
                return [
                    "status" => "error",
                    "message" => "Fallo al crear los errores de la información del aspirante: " . $exception->getMessage(),
                    "code" => $exception->getCode()
                ];
            }
        }
    }

    /**
     * Actualiza la información de verificación del aspirante.
     *
     * Este método actualiza el estado de verificación de un aspirante en la base de datos.
     * Si el estado de verificación es aprobado (1), se eliminan los errores previos asociados.
     *  Luego, se realiza la actualización llamando al procedimiento almacenado UPDATE_CHECK_APPLICANT_APPLICATIONS().
     *
     * @param int $idCheckApplicant ID único del aspirante a verificar.
     * @param boolean $verificationStatus Estado de verificación (1: aprobado, 0: rechazado).
     * @param boolean $revision_status Estado de revisión asociado al Check del aspirante.
     * @param string $descriptionGeneralCheck Descripción general del estado de verificación.
     * @param array $errorData Arreglo que contiene información adicional sobre errores en campos especificos a manejar.
     *
     * @return array Retorna un arreglo con el estado de la operación, un mensaje descriptivo y, opcionalmente, un código de error.
     *
     * @throws InvalidArgumentException Si el ID del aspirante no es un entero válido.
     * @throws Exception Si ocurre un error durante la eliminación de errores o la ejecución del procedimiento almacenado.
     */
    public function updateCheckApplicant($idCheckApplicant, $verificationStatus, $revision_status,$descriptionGeneralCheck, $errorData){
        $mail = new mail();
        $currentDate = new DateTime();
        $jsonDate = json_encode($currentDate);
        $decodedDate = json_decode($jsonDate, true);
        $dateOnly = (new DateTime($decodedDate['date']))->format('Y-m-d');
        $validationChecks = $this->validationCheckError($idCheckApplicant,$verificationStatus,$errorData);
        if($validationChecks['status'] =='success' ){
            try {
            if (!is_int($idCheckApplicant)) {
                throw new InvalidArgumentException("No se han ingresado los parámetros correctos.");
            }            
            
            $this->connection->execute_query("CALL UPDATE_CHECK_APPLICANT_APPLICATIONS($idCheckApplicant, $verificationStatus,'$dateOnly', $revision_status,'$descriptionGeneralCheck')");
            $this->connection->commit();
           // $sendMail = $this->sendStatusNotificationAplications($idCheckApplicant,$verificationStatus,$revision_status,$errorData,$descriptionGeneralCheck);
       
            $sendMail = true;
            if($sendMail==true){
                return [
                    "status" => "success",
                    "message" => "Información del aspirante actualizada correctamente Y notificado con exito."
                ];
            }else{
                return [
                    "status" => "success",
                    "message" => "Información del aspirante actualizada correctamente y  no notificado con exito"
                ];
            }

            } catch (Exception $exception) {
            $this->connection->rollback();
            return [
                "status" => "error",
                "message" => "Excepción en UPDATE_CHECK_APPLICANT_APPLICATIONS() capturada: " . $exception->getMessage(),
                "code" => $exception->getCode()
            ];
        }
        }
    }

    public function getDataApplicantStatusAplicationsMail($idCheckApplicant){
        try {
            if (!is_int($idCheckApplicant)) {
                throw new InvalidArgumentException("No se han ingresado los parámetros correctos.");
            }            
            $dataApplicant = $this->connection->execute_query("GET_DATA_APPLICANT_APPLICATIONS_MAIL($idCheckApplicant)");
            if ($dataApplicant) { 
                if ($dataApplicant->num_rows == 1) { 
                    $mailDataApplicant=  $dataApplicant->fetch_assoc();
                    return [
                        "status" => "success",
                        "mailDataApplicant" => $mailDataApplicant
                    ];
                } else {
                    return [
                        "status" => "warning",
                        "message" => "Se logro encontrar la informacion del aspirante para su notificacion del estado de su aplicacion"
                    ];
                }
            } else {
                return [
                    "status" => "error",
                    "message" => "Error en el procedimiento GET_DATA_APPLICANT_APPLICATIONS_MAIL(): " . $this->connection->error
                ];
            }
        } catch (Exception $exception) {
            return [
                "status" => "error",
                "message" => "Excepción en getDataApplicantStatusAplicationsMail() capturada: " . $exception->getMessage(),
                "code" => $exception->getCode()
            ];
        }
    }

    /**
     * Obtiene toda la información necesaria para enviársela al aspirante por correo electrónico
     * sobre el estado de su aplicación.
     *
     * @param int $idCheckApplicant Identificador del aspirante para verificar la aplicación.
     * @param int $verificationStatus Estado de verificación (1 si es correcta, 0 si tiene errores).
     * @param int $revision_status Estado de revisión (1 si ya fue revisada, 0 si no ha sido revisada).
     * @param array $errorData Datos sobre los errores encontrados en caso de que existan.
     * @param string $descriptionGeneralCheck Descripción general del resultado de la revisión.
     *
     * @return array Información estructurada para enviar por correo al aspirante.
     */
    public function  applicantApplicationStatusMailNotification($idCheckApplicant,$verificationStatus,$revision_status,$errorData,$descriptionGeneralCheck){
        if($revision_status==1){
            $dataMailApplicant = $this->getDataApplicantStatusAplicationsMail($idCheckApplicant);
            if($dataMailApplicant['status']=='success'){
                $dataApplicant = $dataMailApplicant['mailDataApplicant'];
                $nameApplicant = $dataApplicant['nameApplicant'];
                $mailApplicant = $dataApplicant['email_applicant'];
                if($verificationStatus==1){  //la informacion del aspirante fue revisada y esta correcta
                    $mailData = [
                        "nombre" => $nameApplicant,
                        "status" => "sucess",
                        "mail"=>$mailApplicant
                    ];
                }else{ //La informacion del aspirante fue revisada pero contiene errores
                    $mailData = [
                        "nombre" => $nameApplicant,
                        "status" => "warning",
                        "mail"=>$mailApplicant,
                        "camposIncorrectos" => $errorData,
                        "descripcion" => $descriptionGeneralCheck
                    ];
                }
                return $mailData;
            }
        }
        return [
            "status"=>"error",
            "message"=>"No se ha revisado el check"
        ];
    }

    /**
     * Envia notificaciones por correo sobre el estado de una aplicación.
     *
     * Este método envía correos electrónicos basados en el estado de verificación y revisión de la aplicación,
     * usando diferentes plantillas según el resultado de la verificación.
     *
     * @param int    $idCheckApplicant        ID del solicitante cuya aplicación se está revisando.
     * @param string $verificationStatus      Estado de la verificación, 1 si son datos correctos.
     * @param string $revision_status         Estado de la revisión, 1 si fue revisada.
     * @param array  $errorData               Array con datos de errores, si los hay.
     * @param string $descriptionGeneralCheck Descripción general del chequeo realizado.
     *
     * @return bool `true` si el correo fue enviado exitosamente, `false` en caso de error.
     */
    public function sendStatusNotificationAplications($idCheckApplicant,$verificationStatus,$revision_status,$errorData,$descriptionGeneralCheck){
        $mail = new mail(); 
        $dataMailNotificationStatusApplications = $this->applicantApplicationStatusMailNotification($idCheckApplicant,$verificationStatus,$revision_status,$errorData,$descriptionGeneralCheck);
        if($dataMailNotificationStatusApplications["status"]=="success"){
            $mail->sendStatusApplicationCorrect($dataMailNotificationStatusApplications["mail"], $dataMailNotificationStatusApplications["nombre"]);
            return true; 
        } elseif($dataMailNotificationStatusApplications["status"]=="warning"){
            $mail->sendStatusApplicationVerify($dataMailNotificationStatusApplications["mail"], $dataMailNotificationStatusApplications["nombre"],$dataMailNotificationStatusApplications["camposIncorrectos"],$dataMailNotificationStatusApplications["descripcion"]);
            return true; 
        }
        return false;
    }

    public function getAllApplicationsByAdminProcess($idAdminProcess){
        try {
            if (!is_int($idAdminProcess)) {
                throw new InvalidArgumentException("No se han ingresado los parámetros correctos en getAllApplicationsByAdminProcess()");
            }            
            $registeredApplicants = $this->connection->execute_query(" CALL GET_APPLICATIONS_BY_ADMIN_PROCESS($idAdminProcess)");
            if ($registeredApplicants) { 
                if ($registeredApplicants->num_rows > 0) {
                    $allApplicants = [];
                    while ($row = $registeredApplicants->fetch_assoc()) {
                        $allApplicants[] = $row;
                    }
                    return [
                        "status" => "success",
                        "AplicantesInscritos" => $allApplicants
                    ];
                } else {
                    return [
                        "status" => "warning",
                        "message" => "Se logro encontrar aspirantes isncritos en el actual proceso de admision"
                    ];
                }
            } else {
                return [
                    "status" => "error",
                    "message" => "Error en el procedimiento GET_APPLICATIONS_BY_ADMIN_PROCESS(): " . $this->connection->error
                ];
            }
        } catch (Exception $exception) {
            return [
                "status" => "error",
                "message" => "Excepción en getAllApplicationsByAdminProcess() capturada: " . $exception->getMessage(),
                "code" => $exception->getCode()
            ];
        }
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
