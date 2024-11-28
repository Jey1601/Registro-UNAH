

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


___
___

## Descripción de código
___ 
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

##### 1.3 post

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
       
        



---
### 3. src
1. **DAO**
    ###### 1.1 **util** 
       **mail.php**
             Este arhivo contiene la clase mail, que regula el comportamiento de envio de correo a aspirantes.

       * **Mail**
            Contiene los métodos necesarios para el envio de los diferentes tipos de correos a los aspirantes en diferentes etapas de su proceso de admisión haciendo uso de la libreria de PHPMailer; de esta implementamos las clases Exception, PHPMailer y SMTP. 

            ```php
                /**
                 * Cargar dependencias
                 */
                require 'PHPMailer/Exception.php';
                require 'PHPMailer/PHPMailer.php';
                require 'PHPMailer/SMTP.php';
                /**
                 * getApplicantsEmail, contiene los métodos necesarios para recuperar la data de los aspirantes incluyento su correo 
                 * dependiendo del proceso en el que se encuentren.
                 * email_templates, contiene los diferentes tipos de mensajes que serán enviados a los aspirantes
                */
                require 'getApplicantsEmail.php';
                require 'email_templates.php';

                /**
                * Clase de PHPMailer
                */
                use PHPMailer\PHPMailer\PHPMailer;

                class  mail{
                /**
                * Configuración de conexión a la base de datos
                * @param int $maxEmailsPerDay limita la cantidad de correos que se pueden enviar por día. Considerando que actualmente las * pruebas se realizan con un correo personal se tomaron en cuenta las limitantes que el mismo tiene, para un ámbito laboral 
                * lo ideal será utilizar un correo corporativo que permite enviar más correos por día.
                */                
                    private $connection;
                    private $mail;
                    private $maxEmailsPerDay = 500;

                    private $host = 'localhost';
                    private $user = 'root';
                    private $password = '12345';
                    private $dbName = 'unah_registration';


                /**
                 * Constructor de la clase mail
                 * Este método permite inicializar la conexión con la base de datos en mysql.
                */
                public function __construct()
                {
                    $this->connection = null;
                    try {
                        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->dbName);
                    } catch (Exception $error) {
                        printf("Failed connection: %s\n", $error->getMessage());
                    }
                }

                /**
                 * Configuración de PHPMailer
                 * Por seguridad utilizamos una contraseña de aplicación obtenida luego de configurar la verificación de dos pasos de 
                 * nuestra cuenta de correo.
                */
                private function PHPMailerConfig() {
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'admisiones.unah.is802@gmail.com';
                    $mail->Password = 'wwdb wedd fcur guyy';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    $mail->setFrom('admisiones.unah.is802@gmail.com', 'Equipo de Admisiones');
                    return $mail;
                }

                /**
                 * Enviar correos electrónicos de manera dinámica
                 * @param string confirmación correos de confirmación de registro de su solicitud para los aspirantes.
                 * @param string exam_results correo que entrega resultados de los diferentes examenes, contraseña y enlace de registro en 
                 * una carrera para los aspirantes.
                 * @param string approved correo que notifica que el aspirante fue aceptado y ya está registrado como estudiante de la 
                 * carrera que eligio.
                 * @param int $maxEmailsPerDay Límite máximo de correos a enviar.
                 * @return void Esta función no retorna un valor explícito.
                 */
                public function sendEmails( $type, $maxEmailsPerDay) {
                    $mail = $this->PHPMailerConfig();

                    //Obtener los usuarios según el tipo de mensaje
                    $result = match ($type) {
                        'confirmation' => getApplicantsForConfirmation($this->connection),
                        'exam_results' => getApplicantsWithResults($this->connection),
                        'approved' => getApprovedApplicants($this->connection),
                        default => null
                    };

                    if (!$result || $result->num_rows === 0) {
                        echo "No se encontraron usuarios para enviar correos.<br>";
                        return;
                    }

                    $emailCount = 0;

                    while ($row = $result->fetch_assoc()) {
                        if ($emailCount >= $maxEmailsPerDay) break;


                        /**
                         * Crear los valores que se reemplazarán en la plantilla del mensaje.
                         * 
                         * @param string full_name, nombre completo del destinatario.
                         * @param string password_user_applicant, contraseña asignada al aspirante.
                         */
                        $placeholders = [
                            'full_name' => $row['full_name'] ?? '',
                            'password_user_applicant' => $row['password_user_applicant'] ?? ''
                        ];

                        $message = getTemplate($type, $placeholders);

                        try {
                            //Configurar el destinatario y el contenido del correo.
                            $mail->addAddress($row['email_applicant']);
                            $mail->isHTML(true);
                            $mail->Subject = 'Admisiones UNAH';
                            $mail->Body = $message;
                            $mail->send();
                            echo "Correo enviado a {$row['full_name']}<br>";
                            $emailCount++;
                        } catch (Exception $e) {
                            echo "Error al enviar correo a {$row['email_applicant']}: {$mail->ErrorInfo}<br>";
                        }

                        //Limpiar las direcciones de correo configuradas en PHPMailer para preparar el siguiente envío.
                        $mail->clearAddresses();
                    }

                    echo "Se enviaron $emailCount correos.<br>";
                }


                /**
                 * Enviar correos electrónicos de confirmación a los aspirantes
                 * @param string $name, nombre completo del aspirante.
                 * @param string $id_application, número de identificación de la solicitud del aspirante.
                 * @param string $email, dirección de correo electrónico del aspirante.
                 * @return void Esta función no retorna un valor explícito.
                 */
                public function sendConfirmation($name,$id_application,$email){
                    $mail = $this->PHPMailerConfig();


                        $placeholders = [
                            'full_name' => $name ?? '',
                            'id_application' =>$id_application ?? ''
                        ];

                        $message = getTemplate('confirmation', $placeholders);

                        try {
                            //Configurar el destinatario y el contenido del correo.
                            $mail->addAddress($email);
                            $mail->isHTML(true);
                            $mail->Subject = 'Admisiones UNAH';
                            $mail->Body = $message;
                            $mail->send();
                        } catch (Exception $e) {
                            echo "Error al enviar correo a {$email}: {$mail->ErrorInfo}<br>";
                        }

                        //Limpiar las direcciones de correo configuradas en PHPMailer para preparar el siguiente envío.
                        $mail->clearAddresses();
                
                }

                /*Ejecutar el sistema: prueba para enviar confirmación
                $connection = DBConection($host, $user, $password, $database);
                sendEmails($connection, 'confirmation', $maxEmailsPerDay);
                $connection->close();*/

                }
            ```

         **getApplicantsEmail.php**
             Este arhivo contiene los métodos necesarios para realizar la extracción de información necesaria sobre los apirantes para enviar los diferentes tipos de correos que notificarán información necesaria para los aspirantes.

        * **Get Applicants Email**
            Contiene los métodos necesarios para el envio de los diferentes tipos de correos a los aspirantes en diferentes etapas de su proceso de admisión haciendo uso de la libreria de PHPMailer; de esta implementamos las clases Exception, PHPMailer y SMTP. 

            ```php
            
                /**
                 * Esta función permite recuperar a los aspirantes que culminarón con la fase de evaluaciones y quienes tienen sus 
                 * resultados registrados en la base de datos, luego de que su proceso de inscripción haya sido correctamente 
                 * verificado.
                 * @param mysqli $connection La conexión a la base de datos.
                 * @return mysqli_result|false Un objeto `mysqli_result` con los datos de los aspirantes si la consulta es exitosa, 
                 * o `false` si ocurre un error.
                 */
                function getApplicantsWithResults($connection) {
                    $sql = "
                        SELECT 
                            Applicants.id_applicant,
                            CONCAT(
                                Applicants.first_name_applicant, ' ',
                                IFNULL(Applicants.second_name_applicant, ''), ' ',
                                IFNULL(Applicants.third_name_applicant, ''), ' ',
                                Applicants.first_lastname_applicant, ' ',
                                IFNULL(Applicants.second_lastname_applicant, '')
                            ) AS full_name,
                            Applicants.email_applicant,
                            UsersApplicants.password_user_applicant,
                            `TypesAdmissionTests`.name_type_admission_tests,
                            `RatingApplicantsTest`.rating_applicant
                        FROM 
                            Applicants
                        LEFT JOIN 
                            Applications ON Applicants.id_applicant = Applications.id_applicant
                        LEFT JOIN 
                            UsersApplicants ON Applications.id_admission_application_number = UsersApplicants.password_user_applicant
                        LEFT JOIN
                            RatingApplicantsTest ON Applications.id_admission_application_number = RatingApplicantsTest.id_admission_application_number
                        LEFT JOIN `TypesAdmissionTests` ON `RatingApplicantsTest`.id_type_admission_tests = `TypesAdmissionTests`.id_type_admission_tests   
                        WHERE 
                            Applicants.status_applicant = 1 AND status_rating_applicant_test =1;";
                    return $connection->query($sql);
                }

                /**
                 * Esta función recupera a los aspirantes para enviarles un correo que les informará el exito del registro de su 
                 * solicitud.
                 * @param mysqli $connection La conexión a la base de datos.
                 * @return mysqli_result|false Un objeto `mysqli_result` con los datos de los aspirantes si la consulta es exitosa, 
                 * o `false` si ocurre un error.
                */
                function getApplicantsForConfirmation($connection) {
                    $sql = "
                        SELECT 
                            Applicants.id_applicant,
                            CONCAT(
                                Applicants.first_name_applicant, ' ',
                                IFNULL(Applicants.second_name_applicant, ''), ' ',
                                IFNULL(Applicants.third_name_applicant, ''), ' ',
                                Applicants.first_lastname_applicant, ' ',
                                IFNULL(Applicants.second_lastname_applicant, '')
                            ) AS full_name,
                            Applicants.email_applicant
                        FROM 
                            Applicants
                        JOIN 
                            Applications ON Applicants.id_applicant = Applications.id_applicant
                        WHERE 
                            Applicants.status_applicant = 1 AND 
                            Applications.status_application = 1;
                    ";
                    return $connection->query($sql);
                }


                /**
                 * Esta función permite recuperar a los aspirantes que ya escogieron una carrera y fueron aprobados y registrados en la 
                 * misma.
                 * @param mysqli $connection La conexión a la base de datos.
                 * @return mysqli_result|false Un objeto `mysqli_result` con los datos de los aspirantes si la consulta es exitosa, 
                 * o `false` si ocurre un error.
                 */
                function getApprovedApplicants($connection) {
                    $sql = "
                        SELECT 
                            Applicants.id_applicant,
                            CONCAT(
                                Applicants.first_name_applicant, ' ',
                                IFNULL(Applicants.second_name_applicant, ''), ' ',
                                IFNULL(Applicants.third_name_applicant, ''), ' ',
                                Applicants.first_lastname_applicant, ' ',
                                IFNULL(Applicants.second_lastname_applicant, '')
                            ) AS full_name,
                            Applicants.email_applicant,
                            Undergraduates.name_undergraduate AS 
                        FROM 
                            Applicants
                        JOIN 
                            Applications ON Applicants.id_applicant = Applications.id_applicant
                        JOIN 
                            ResolutionIntendedUndergraduateApplicant ON 
                                Applications.id_admission_application_number = ResolutionIntendedUndergraduateApplicant.id_admission_application_number
                        JOIN 
                            Undergraduates ON 
                                ResolutionIntendedUndergraduateApplicant.intended_undergraduate_applicant = Undergraduates.id_undergraduate
                        WHERE 
                            Applicants.status_applicant = 1 AND 
                            ResolutionIntendedUndergraduateApplicant.resolution_intended = 1 AND 
                            ResolutionIntendedUndergraduateApplicant.status_resolution_intended_undergraduate_applicant = 1;
                    ";
                    return $connection->query($sql);
                }
                ```

        **email_templates.php**
             Este arhivo contiene plantillas para los diferentes tipos de mensaje de notificación que se le pueden enviar a los aspirantes.

        * **Email Templates**
            Contiene un metodo que permite almacenar plantillas que posteriormente serán invocadas para enviar información importante para los aspirantes; actualmente se les notifica cuando su solicitud para realizar los diferentes examenes de admisión es correcatamente registrada para comenzar con su proceso. Se les notifica con un mensaje, una vez que los resultados de sus examenes fueron registrados y su documentación debidamente validada, sus puntajes obtenidos en los examenes, una contraseña y un enlace al cual podrán acceder, ingresar la contraseña suministrada y seleccionar la carrera en la que desean registrarse. Y finalmente, un mensaje que notifica una vez el aspirante es aceptado y registrado como estudiante de la carrera que eligio previamente.

            ```php
                
                /**
                 * Plantillas de mensajes predeterminados 
                 * Esta función selecciona una plantilla HTML según el tipo de mensaje solicitado 
                 * y reemplaza los marcadores de posición (placeholders) con valores específicos proporcionados.
                 * @param string $templates, plantllas de mensaje
                 * @param string $id_application, número de identificación de la solicitud del aspirante.
                 * @param string $email, dirección de correo electrónico del aspirante.
                 * @param array $placeholders Arreglo asociativo con los valores a reemplazar en la plantilla.
                 * Las claves corresponden a los nombres de los marcadores en la plantilla.
                 * Ejemplo: ['full_name' => 'Juan Pérez'].
                 * @return string La plantilla de correo con los valores reemplazados. Si el tipo no es válido, retorna una cadena 
                 * vacía.
                 */
                function getTemplate($type, $placeholders = []) {
                    $templates = [
                        'confirmation' => "
                            <html>
                            <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                                <h2 style='color: #0056b3;'>Estimado(a) {{full_name}},</h2>
                                <p>Gracias por enviar tu solicitud de admisión. Nos complace informarte que tu solicitud ha sido registrada exitosamente.</p>
                                <p><strong>Número de solicitud:</strong> {{id_application}}</p>
                                <p>El siguiente paso en el proceso es la verificación de los datos proporcionados. En breve recibirás un correo nuestro informándote si la información ingresada es correcta o si es necesario realizar algún ajuste.</p>
                                <p>Agradecemos tu interés en formar parte de nuestra comunidad universitaria. Estamos aquí para ayudarte en todo lo necesario durante este proceso.</p>
                                <p>Atentamente,</p>
                                <p style='color: #0056b3;'><strong>Equipo de Admisiones</strong></p>
                                <p style='font-size: 12px; color: #777;'>Este es un mensaje automático, por favor no respondas a este correo. Si tienes preguntas, contáctanos a través de nuestros canales oficiales.</p>
                            </body>
                            </html>",
                        'exam_results' => "
                            <html>
                            <body>
                                <h2>Hola, {{full_name}}</h2>
                                <p>Te informamos que obtuviste los siguientes resultados en tu examen de admisión para 
                                la máxima casa de estudios:</p>
                                {{exams_details}}
                                <p>Puedes acceder al enlace adjunto en este correo para seleccionar la carrera en la que
                                te gustaría inscribirte. Usa tu número de identidad como usuario y la siguiente
                                contraseña para ingresar al sitio.</p>
                                <p>Contraseña: {{password_user_applicant}}</p>
                                <a href='https://www.facebook.com'>Elige tu carrera aquí</a>
                                <p>Saludos,<br>El equipo de Admisiones</p>
                            </body>
                            </html>",
                        'acceptance' => "
                            <html>
                            <body>
                                <h2>Hola, {{full_name}}</h2>
                                <p>Fuiste aceptado en {{career_name}}. ¡Bienvenido Pumita!</p>
                            </body>
                            </html>"
                    ];
                    //Obtener la plantilla correspondiente al tipo solicitado.
                    $message = $templates[$type] ?? '';

                    //Reemplazar los marcadores de posición con los valores proporcionados.
                    foreach ($placeholders as $key => $value) {
                        $message = str_replace("{{{$key}}}", $value, $message);
                    }
                    //Retornar la plantilla generada o una cadena vacía si el tipo no es válido.
                    return $message;
                }
            ```
    ###### 1.2 **PHPMailer**
       **Exception.php**
            Este arhivo contiene la clase Exception de la libreria PHPMailer que permite el manejo de excepciones lanzadas por PHPMailer.
            Provee mensajes de error detallados y específicos cuando algo falla al configurar o enviar un correo.
       **PHPMailer.php**
            Clase principal de la librería y se encarga de manejar todo el proceso de creación y envío de correos electrónicos.
            *Soporta mensajes en HTML, texto plano, con adjuntos, y configuraciones avanzadas.
       **SMTP.php**
            Esta clase maneja las conexiones y la comunicación directa con servidores SMTP. 
            Implementa el protocolo SMTP para enviar correos electrónicos.
            Administra conexiones seguras usando SSL o TLS.
            Administra el intercambio de comandos y respuestas entre el cliente y el servidor SMTP.
            Compatible con autenticación estándar y segura (LOGIN, PLAIN, CRAM-MD5).
                
             
    
---
### 4. test