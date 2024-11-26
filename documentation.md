

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
---
### 4. test