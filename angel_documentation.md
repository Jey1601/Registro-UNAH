
# Documentación de Sistema de Registro UNAH

## Estructura del proyecto

<!-- Mencionar lista de carpeta y su uso, qué es lo que debería contener -->
---
En el actual proyecto se encuentra una lista de carpetas que contienen contenido agrupado entre sí de manera logica de la siguiente forma:

1. **db-scripts** : Contiene todos los scripts para la creación de la base de datos.
    * **ddl** : Contiene los scripts relacionados a data definition languaje de la base, creación de tablas, definición de relaciones etc.
    * **dml** : Contiene los scripts relacionados a data modelation languajes de la base, es decir, creación de procesos almacenados, inserts etc.
2. **public** : Contiene todos los elementos publicos del cliente.

    * **api** : Contiene todas las apis necesarias para el funcionamiento del proyecto.
        * **delete** : Contiene todas las apis relacionadas con eliminación de datos.
        * **get** : Contiene todas las apis relacionadas con obtención de datos.
        * **post** : Contiene todas las apis relacionadas con creación de elementos.

        * **put** : Contiene todas las apis relacionadas con actualización de elementos.
    * **assets**: Contiene todos los elementos que necesita el frontend para poder funcionar correctamente.
        * **css** : Contiene las hojas de estilo aplicadas en los archivos html.
        * **js** : Contiene todos los arhivos exejutables de javascript generados durante el proyecto, en esta misma rama, también estan los archivos principales que son implementados en html y hacen uso de los distintos módulos, dichos archivos divididos por carpetas al módulo del proyecto que pertenecen.
            * **Módules** : Contiene todos los archivos javascript que pueden exportarse como módulos.
                 * ***behavior*** : contiene todos los archivos javascript que controlan el comportamiento de la apliación web,dichos archivos divididos por carpetas al módulo del proyecto que pertenecen.
                 * ***request*** : contiene todos los archivos javascript para la consulta de apis o endpoints,dichos archivos divididos por carpetas al módulo del proyecto que pertenecen.
        * **img** : Contiene todos las imagenes del proyecto, así como iconos, dentro de ella, se dividen por el modúlo al que pertenecen, o sí son de uso general, se encuentran en una carpeta accesible por todos los archivos, como los el caso de icons, que agrupa todos los iconos utilizados en el proyecto.
        * **font** : Contiene todos los archivos de fuentes utilizados en el proyecto.
    * **views** : contiene todas las vistas escritas en html de los distintos módulos, agrupados en carpetas con la misma división.

3. **src** :Conteine el códgio fuente del proyecto.
    * **DAO** : Contiene los controladores de las distintas entidades del sistema, dentro de dichos controladores, se encuentran los metodos que permiten alterar o accerder a la información que se encuentra en la base de datos, además de difinir la lógica del negocio.
4. **test**

---
---

## Descripción de código

---
<!-- Dentro del apartado de cada carpeta, crea un titulo para tu archivo si no existe, de exitir crea un nombre par el metodo que creaste, explicando, su paramentros, su salida esperada y su comportamiento.-->

<!-- si un archivo tiene varias clases, primero dar una pequeña descripcioón de lo que contiene el arhcivo, luego listar las clases una pequeña descripción de ella y luego explicación de metodos-->

### 1. db-scripts

#### dml

#### ddl

---

### 2. public

#### 1. api

##### 1.1 delete

##### 1.2 get

1. **applicant**
    * **applicantDownloadCSV.php**
        Endpoint para la descarga de un archivo CSV con información de interés de los aspirantes. Información antes de la evaluación de los exámenes.

        ```php
            <?php
            include_once "../../../../src/DAO/ApplicantDAO.php";

            //Establecer cabeceras para que el navegador entienda que es un archivo descargable
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="aspirantes.csv"');
            header('Cache-Control: no-cache, no-store, must-revalidate');

            //Crear objecto DAO
            $applicantDAO = new ApplicantDAO();
            $applicants = $applicantDAO->getApplicantsInfoCSV();

            echo $applicants;
            ?>
        ```

##### 1.3 post

1. **admissionAdmin**
    * **authAdmissionAdmin.php**
        Endpoint para la autenticación de los usuarios administradores de admisiones.

        ```php
            <?php
            include_once '../../../../src/DAO/AdmissionAdminDAO.php';

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode([
                    'success' => false,
                    'message' => 'Metodo no permitido.'
                ]);
                exit;
            }

            $inputBody = json_decode(file_get_contents('php://input'), true);

            $userAdmissionAdmin = trim($inputBody['userAdmissionAdmin']);
            $passwordAdmissionAdmin = trim($inputBody['passwordAdmissionAdmin']);

            $regexValidationPassword = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[+*\-_])[A-Za-z\d+*\-_]{8,}$/';
            $validation = preg_match($regexValidationPassword, $passwordAdmissionAdmin);
            if (!$validation) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Credenciales invalidas.'
                ]);
                exit;
            }

            $auth = new AdmissionAdminDAO();
            $response = $auth->authAdmissionAdmin($userAdmissionAdmin, $passwordAdmissionAdmin);

            echo json_encode($response);

            ?>
        ```

    * **uploadRatingsCSV.php**
        Endpoint para la carga de un archivo CSV para la subida de notas de los exámenes de admisión.

        ```php
            <?php
            include_once '../../../../src/DAO/AdmissionAdminDAO.php';

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvFile'])) {
                $fileTmpPath = $_FILES['csvFile']['tmp_name'];
                $csvController = new AdmissionAdminDAO();
                $response = $csvController->readCSVFile($_FILES['csvFile']);
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Metodo no permitido.'
                ];
                exit;
            }

            echo json_encode($response);

            ?>
        ```

2. **applicant**
    * **authApplicant**
        Endpoint para la autenticación de usuarios de aspirantes.

        ```php
            <?php
            include_once '../../../../src/DAO/ApplicantDAO.php';

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode([
                    'success' => false,
                    'message' => 'Metodo no permitido.'
                ]);
                exit;
            }

            //Decodificacion del body
            $inputBody = json_decode(file_get_contents('php://input'), true);

            //Captura y limpieza de datos
            $numID = trim($inputBody['numID'] ?? '');
            $numRequest = trim($inputBody['numRequest'] ?? '');

            //Validacion de numero de identidad
            $regexValidationID = '/(0|1)[1-8][0-2][0-8](1|2)(0|9)\d{7}$/';
            $validation = preg_match($regexValidationID, $numID);
            if (!$validation) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Credenciales invalidas.'
                ]);
                exit;
            }
            $numRequest = intval($numRequest);

            $auth = new ApplicantDAO();
            $response = $auth->authApplicant($numID, $numRequest);

            echo json_encode($response);
            ?>
        ```

3. **tokenValidation.php**
    Endpoint para la validación del token de la sesión. Precarga a páginas HTML.

    ```php
        <?php
        include_once '../../../src/DAO/util/tokenVerification.php';

        header("Content-Type: application/json");

        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['token']) || empty($data['token'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Token nulo.'
            ]);
            exit;
        } else {
            $token = $data['token'];
            $typeUser = $data['typeUser'];
            $tokenVerification = new TokenVerification();
            $response = $tokenVerification->tokenVerification($token, $typeUser);

            if ($response['success'] === false) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Token invalido.'
                ]);
                exit;
            } else {
                echo json_encode([
                    'success' => true,
                    'message' => 'Validacion exitosa.',
                    'tokenExpiration' => $response['tokenExpiration']
                ]);
            }
        }
        ?>
    ```

##### 1.4 put

#### 2. assets

##### 2.1 js

###### 2.1.1 **modules

1. **behavior**

2. **request**
    * **inscription.msj**
        Este arhivo contiene la clase Inscription, que regula el comportamiento de inscripción de aspirantes.

        * **Inscription**
        Contiene los métodos necesarios para poder obtener la data desde el formuario de inscripciones, verificarla y hacer la petición de insersción en la base de datos.

        ```javascript
            /**
             * regular_expressions, permite verificar la estructura de los datos ingresados.
             * Alert, permita desplegar alerts ubicados en puntos de la vista html.
             */

            import { regular_expressions } from "./configuration.mjs";
            import { Alert } from "./support.mjs";

            class Inscription {
               

                static createModalInstance() {
                    this.modalInstance = new Modal();
                }

                /**
                 * Obtiene los datos del formulario de inscripción, los valida
                 * y realiza una petición de inserción en la base de datos.
                 * 
                 * @returns {Promise} Respuesta de la API tras la inserción.
                */
                static async getData() {
                    const formData = new FormData(inscriptionForm);
                    
                      if (this.validateFileSize(formData.get("applicantCertificate"))) {
                        if (this.DataCorrect(formData)) {
                            Alert.display("Estamos cargando su información", "success");

                            this.insertData(formData);
                        } else {
                            Alert.display("Uno o más datos no están correctos", "danger");
                        }
                      }
                }

                /**
                 * @param{object} FormData de la información del formulario
                 * @returns {boolean} Respuesta del metodo tras verificar la concordancia .
                 * con expresiones regulares.
                 */


                static DataCorrect(formData) {
              
                }

                /**
                 * Hace la petición al apit de insertApplicant para insertar la
                 * la información previamente verificada
                 * @param{object} FormData de la información del formulario
                 * @returns {Promise} Respuesta de la API tras la inserción.
                 */

                static async insertData(formData) {
                    try {
                    // Realizar la solicitud POST usando fetch
                    const response = await fetch(
                        "../../../api/post/applicant/insertApplicant.php",
                        {
                        method: "POST",
                        body: formData,
                        }
                    );

                    const result = await response.json(); // Esperamos que la respuesta sea convertida a JSON

                    Alert.display(result.message, "warning");
                    } catch (error) {
                    console.error("Error:", error); // Manejamos el error si ocurre
                    Alert.display("Hubo un error al cargar la información", "danger");
                    }
                }

                 /**
                  * Recibe la imagen insertado en el formulario y valida el tamaño 
                  * máximo aceptado que es de 16Mb según la base de datos.
                  * @param{object} arhivo de imagen obtenido del formulario
                  * @returns {boolean} Respuesta del metodo tras verificar el tamaño.
                */
                static validateFileSize(file) {
                }
                
           }   
        ```

    * **login.msj**
        Módulo que contiene la clase Login que maneja las autenticaciones de los usuarios. Utiliza la clase Alert como auxiliar para los mensajes de fallos.

        ```javascript
            import { Alert } from "../behavior/support.mjs";
        ```

        * **Login**
        Clase que contiene métodos auxiliares para la autenticación de los usuarios o el manejo del resultado de la misma.

        ```javascript
        class Login {
            static getDataApplicant(){
                //Se obtiene la data del aplicante desde el login
                const applicant_identification = document.getElementById('applicantId').value;
                const applicant_application_number = document.getElementById('applicationNumber').value;
                
                const credentials = {
                    applicant_identification,
                    applicant_application_number
                };
                
                if(this.regexValidation(credentials)){
                    alert("Estamos cargando su información");
                    //Call the php method to insert in the database
                }else{
                    alert("Uno o más datos no están correctos");
                }

                console.log(credentials);
            }

            static async authApplicant() {
                const idNum = document.getElementById('id_applicant').value;
                const numReq = document.getElementById('id_application').value;

                const credentials = {
                    "numID": idNum,
                    "numRequest": numReq
                }

                try{
                    fetch('../../../../api/post/applicant/authApplicant.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(credentials)
                    }).then(response => response.json()).then(result => {
                    console.log(result);

                    if (result.success) {
                        sessionStorage.setItem('token', result.token);
                        sessionStorage.setItem('typeUser',result.typeUser);
                        window.location.href = '../../../../views/admissions/results.html';
                    } else {
                        Alert.display("warning", "Error en la autenticacion", result.message);
                    }
                    });
                } catch (error) {
                    console.log('Error al mandar la peticion: ',error);
                }
            }

            static async authAdmisionAdmin() {
                const username = document.getElementById('admissionsUser').value;
                const password = document.getElementById('admissionsPassword').value;

                const credentials = {
                    "userAdmissionAdmin": username,
                    "passwordAdmissionAdmin": password
                }

                try {
                    fetch('../../../api/post/admissionAdmin/authAdmissionAdmin.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(credentials)
                    }).then(response => response.json()).then(result => {
                        console.log(result);
                        
                        if (result.success) {
                            sessionStorage.setItem('token', result.token);
                            sessionStorage.setItem('typeUser',result.typeUser);
                            window.location.href = '../../../../views/administration/admissions-admin.html';
                        } else {
                            Alert.display("warning", "Error en la autenticacion", result.message);
                        }
                    })
                } catch (error) {
                    console.log('Error al mandar la peticion: ',error);
                }
            }

            static regexValidation(credentials){
                if (
                    
                    regular_expressions.idNum.test(credentials.applicant_identification) &&             // Validate ID number
                    credentials.applicant_application_number != " "      
                ) {
                    return true;  // If all validations pass, return true
                } else {
                    return false; // If any validation fails, return false
                }
            }

            static getPayloadFromToken(token) {
                const payloadBase64 = token.split('.')[1]; // Obtén el payload
                const payload = atob(payloadBase64); // Decodifica de Base64
                return JSON.parse(payload); // Convierte el JSON a un objeto
            }

            static logout(url){
                sessionStorage.setItem('token','');
                window.location.href = url;
            }
            }

            export{Login};
        ```

    * **uploadCSVAdmissionAdmin.mjs**
        Módulo que contiene la función submitCSVFile que hace la petición asíncrona para la carga de un archivo CSV con las notas de los exámenes de admisión de los aspirantes. Utiliza las clases Alert y Login para los mensajes de error y para el cierre de sesión, respectivamente.

        ```javascript
            import { Alert } from "../behavior/support.mjs"; 
            import { Login } from "./login.mjs";

            async function submitCSVFile() {
                
                const formInscriptionGrades=document.getElementById('formInscriptionGrades');

                formInscriptionGrades.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    const file_input = document.getElementById('csvFile');
                    const form_data = new FormData();

                    if (file_input.files.length > 0) {
                        form_data.append('csvFile', file_input.files[0]);
                        
                        try {
                            const response = await fetch ('../../../api/post/admissionAdmin/uploadRatingsCSV.php', {
                                method: 'POST',
                                body: form_data
                            });

                            const result = await response.json();
                            formInscriptionGrades.reset();
                            Alert.display(result.message, "warning");
                        } catch (error) {
                            Alert.display("No se pudo cargar el archivo", "warning");
                        }
                    } else {
                        alert('Por favor, seleccionar un archivo CSV.');
                    }
                })
            }

            const btn_upload = document.getElementById('btnUpload');
            btn_upload.addEventListener('click', submitCSVFile);

            const logoutBtn = document.getElementById('logoutBtn');
            logoutBtn.addEventListener('click', function(event){
                event.preventDefault();
                Login.logout('../../index.html')
            });
        ```

3. **tokenValidation.js**
    Script que maneja la validación del token almacenado en sessionStorage antes de la carga de la página HTML.

    ```javascript
        document.addEventListener('DOMContentLoaded', () => {
            const token = sessionStorage.getItem('token');
            const type_user = sessionStorage.getItem('typeUser');

            if(!token) {
                console.log("No se encontro token en el sessionStorage.");
                window.location.href = '../../../../index.html';
                return;
            }

            fetch('../../api/post/tokenValidation.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    'token': token,
                    'typeUser': type_user
                })
            }).then(response => response.json()).then(data => {
                if (data.success === false) {
                    window.location.href = '../../../../index.html';
                    console.log(data.message);
                } else {
                    console.log(data.message);
                    console.log(data.tokenExpiration);
                }
            }).catch (error => {
                console.error("Error en la validacion del token: ", error);
                window.location.href = '../../../../index.html';
            });
        });
    ```

---

### 3. src

#### 1. DAO

1. **util**
    Directorio que contiene scripts de PHP auxiliares.

    * **PHPMailer**

    * **jwt.php**
    Script que contiene la clase JWT para la creación y verificación de JSON Web Token.
        * **JWT**
        Clase que contiene el método generateToken para la creación de tokens JWT y validateToken para comprobar la estructura de un token y saber si es válido. Adicionalmente contiene los métodos auxiliares base64UrlEncode y base64UrlDecode para la codificación y decodificación de una cadena de texto base64 para que sea seguro mandarla por una URL.

        ```php
            <?php
            class JWT {
                private static $secret_key = 'is802';

                /**
                * Metodo para genera un token JWT a partir de un payload.
                *
                * @param array $payload Información del aspirante que se almacenara en el token.
                * @param int $expTime Tiempo en segundos hasta la expiración del token.
                * @return string Token JWT generado.
                */
                public static function generateToken(array $payload, int $expTime = 3600):string {
                    $header = json_encode([
                        'alg' => 'HS256',
                        'typ' => 'JWT'
                    ]);

                    $payload['exp'] = time() + $expTime; //Tiempo de expiracion del token

                    $base64Header = self::base64UrlEncode($header);
                    $base64Payload = self::base64UrlEncode(json_encode($payload));

                    $signature = hash_hmac('sha256', $base64Header.".".$base64Payload, self::$secret_key, true);
                    $base64Signature = self::base64UrlEncode($signature);

                    return $base64Header.".".$base64Payload.".".$base64Signature;
                }

                /**
                * Metodo de validacion de un token JWT y verificacion de su firma y expiración.
                *
                * @param string $jwt Token JWT a validar.
                * @return bool|array Retorna el payload si el token es válido, o `false` si no lo es.
                */
                public static function validateToken(string $jwt):bool|array {
                    $partes = explode('.', $jwt);
                    if(count($partes) !== 3) {
                        return false; //Formato invalido
                    }

                    [$base64Header, $base64Payload, $base64Signature] = $partes;
                    $payload = json_decode(self::base64UrlDecode($base64Payload), true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        return false; //Payload invalido
                    }

                    //Verificacion de firma
                    $signature = hash_hmac('sha256', $base64Header.".".$base64Payload, self::$secret_key, true);
                    $base64SignatureCheck = self::base64UrlEncode($signature);
                    if (!hash_equals($base64Signature, $base64SignatureCheck)) {
                        return false;
                    }

                    //Verificacion de expiracion
                    if (isset($payload['exp']) && time() >= $payload['exp']) {
                        return false; //Token expirado
                    }
                    
                    return $payload; //Token valido
                }

                /**
                * Metodo para codificar una cadena en Base64 de manera segura para URL.
                *
                * @param string $data La cadena a codificar.
                * @return string La cadena codificada en Base64 URL seguro.
                */
                private static function base64UrlEncode(string $data):string {
                    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
                }

                /**
                * Metodo para decodificar una cadena en Base64 de URL seguro.
                *
                * @param string $data La cadena codificada en Base64 URL seguro.
                * @return string La cadena decodificada.
                */
                private static function base64UrlDecode(string $data):string {
                    $padding = 4 - (strlen($data) % 4);
                    if ($padding !== 4) {
                        $data .= str_repeat('=', $padding);
                    }

                    return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
                }
            }
            ?>
        ```

    * **tokenVerification.php**

2. **AcceptanceAdmissionProcessDAO.php**
3. **AdmissionAdminDAO.php**
4. **AdmissionProccessDAO.php**
5. **AdmissionTestAdmissionProcessDAO.php**
6. **ApplicantDAO.php**
    Objeto de acceso a datos y controlador de aspirantes. Contiene los métodos para manejar las peticiones relacionadas con los aspirantes.

    ```php
    ```

7. **CareerDAO.php**
8. **DocumentValidationAdmissionProcessDAO.php**
9. **DownloadApplicantAdmittedInformationAdmissionProcessDAO.php**
10. **InscriptionAdmissionProcessDAO.php**
11. **RectificationPeriodAdmissionProcessDAO.php**
12. **RegionalCenterDAO.php**
13. **RegistrationRatingAdmissionProcessDAO.php**
14. **SendingNotificationsAdmissionProcessDAO.php**

---

### 4. test
