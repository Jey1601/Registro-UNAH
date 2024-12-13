import { Alert, Cell, Modal } from "../behavior/support.mjs";

class EnrollmentProcess {
  static path = "../../../../";viewSections

/**
 * Verifica el estado del proceso de inscripción en el sistema.
 * Realiza una solicitud a un servicio para obtener la información del estado del proceso de inscripción.
 * Si la solicitud es exitosa, devuelve los datos obtenidos. En caso de error, muestra un mensaje de error en consola y retorna un arreglo vacío.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-11
 * @returns {Promise<Object[]>} - Un arreglo de objetos con los datos del estado del proceso de inscripción, si la solicitud es exitosa. Si ocurre un error, retorna un arreglo vacío.
 */
  static async verifyEnrollmentProcessStatus() {
    try {
      const response = await fetch(
        this.path +
          "api/get/enrollment/VerifyEnrollmentProcessStatus.php"
      );  
 
      if (!response.ok) {
        throw new Error("Error en la solicitud: " + response.status);
      }

      const data = await response.json();

      
      return data;
     
    } catch (error) {
        console.log(error);
      return []; 
    }
  }

  /**
   * Verifica las fechas del proceso de inscripción en el sistema.
   * Realiza una solicitud a un servicio para obtener la información de las fechas asociadas al proceso de inscripción.
   * Si la solicitud es exitosa, devuelve los datos obtenidos. En caso de error, muestra un mensaje de error en consola y retorna un arreglo vacío.
   * 
   * @author Jeyson Espinal (20201001015)
   * @created 2024-12-11
   * @returns {Promise<Object[]>} - Un arreglo de objetos con los datos de las fechas del proceso de inscripción, si la solicitud es exitosa. Si ocurre un error, retorna un arreglo vacío.
   */
  static async verifyDatesEnrollmentProcess() {
    try {
      const response = await fetch(
        this.path + "api/get/enrollment/VerifyDatesEnrollmentProcess.php"
      );  
     
      if (!response.ok) {
        throw new Error("Error en la solicitud: " + response.status);
      }

      const data = await response.json();
   
      
      return data;
     
    } catch (error) {
        console.log(error);
      return []; 
    }
  }


  /**
 * Verifica el estado del proceso de inscripción de un estudiante específico.
 * Realiza una solicitud POST al servidor enviando el ID del estudiante y devuelve el estado de la inscripción.
 * Si la solicitud es exitosa y el proceso no está completo, se muestra un mensaje de error utilizando `Alert.display`.
 * En caso de error en la solicitud, se registra el error en la consola y retorna `null`.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-11
 * @param {number} idStudent - El ID del estudiante cuyo estado de inscripción se desea verificar.
 * @returns {Promise<Object|null>} - Retorna un objeto con el estado del proceso de inscripción del estudiante si la solicitud es exitosa. Si hay un error, retorna `null`.
 */

  static async verifyStatusEnrollmentProcessStudent(idStudent){
    const data = {
      idStudent: idStudent
    };

   
    try {
      const response = await fetch(
        this.path +
          "api/post/enrollment/verifyStatusEnrollmentProcessStudent.php",
        { 
          method: "POST",  
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );

      const responseData = await response.json();
     
      
      if (responseData.status != "success") {
        Alert.display(
          responseData.status,
          "oh",
          responseData.message,
          this.path
        )
      } 
      
     return responseData;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }    
  } 

  /**
 * Obtiene las clases pendientes de un estudiante específico.
 * Realiza una solicitud POST al servidor enviando el ID del estudiante y devuelve las clases pendientes de inscripción.
 * Si la solicitud es exitosa y hay clases pendientes, las retorna en el campo `data`. Si hay un error en la solicitud, se muestra un mensaje utilizando `Alert.display`.
 * En caso de error, se registra el error en la consola y retorna `null`.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-12
 * @param {number} studentId - El ID del estudiante para obtener las clases pendientes.
 * @returns {Promise<Array|null>} - Retorna un array con las clases pendientes si la solicitud es exitosa, o `null` en caso de error.
 */
  static async getPendingClassesStudent(studentId){
    const data = {
      student_id: studentId
    };

   
    try {
      const response = await fetch(
        this.path +
          "api/post/enrollment/PostPendingClassesStudent.php",
        {
          method: "POST",  
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );

      const responseData = await response.json();
     
      
      if (responseData.status != "success") {
        Alert.display(
          responseData.status,
          "oh",
          responseData.message,
          this.path
        )
      } 

     return responseData.data;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }    
  } 

  /**
 * Obtiene las secciones de una clase específica para un estudiante.
 * Realiza una solicitud POST al servidor enviando el ID del estudiante y el ID de la clase.
 * Si la solicitud es exitosa, retorna las secciones correspondientes al estudiante para esa clase en el campo `data`. 
 * En caso de error en la solicitud, se muestra un mensaje utilizando `Alert.display`. 
 * Si ocurre un error en el proceso, se registra en la consola y la función retorna `null`.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-11
 * @param {number} student_id - El ID del estudiante para obtener las secciones de la clase.
 * @param {number} class_id - El ID de la clase para la cual obtener las secciones.
 * @returns {Promise<Array|null>} - Retorna un array con las secciones de la clase si la solicitud es exitosa, o `null` en caso de error.
 */
  static async getClassSectionsForStudent(student_id,class_id ){
    const data = {
      student_id:student_id, 
      class_id :class_id
    };

   
    try {
      const response = await fetch(
        this.path +
          "api/post/enrollment/PostClassSectionsForStudent.php",
        {  
          method: "POST",  
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );

      const responseData = await response.json();
      

      
      if (responseData.status != "success") {
        Alert.display(
          responseData.status,
          "oh",
          responseData.message,
          this.path
        )
      } 
   
     return responseData.data;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }    
  } 

/**
 * Rellena un `<select>` con opciones de departamentos a partir de un objeto de datos.
 * La función toma un objeto `data` donde las claves representan los nombres de los departamentos.
 * Se crea una opción para cada departamento y se agrega al `<select>` especificado por `departmentSelect`.
 * Si el objeto `data` está vacío, solo se muestra la opción predeterminada "Seleccione un departamento".
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-11
 * @param {Object} data - Un objeto donde las claves son los nombres de los departamentos.
 * @param {HTMLSelectElement} departmentSelect - El elemento `<select>` donde se agregarán las opciones de departamentos.
 * @returns {void} - No retorna ningún valor. Modifica directamente el contenido del elemento `<select>`.
 */

static    populateDepartments(data,departmentSelect ) {
  departmentSelect.innerHTML ='';
  departmentSelect.innerHTML = '<option value="">Seleccione un departamento</option>';
  // Iterar sobre los departamentos
  for (const departmentName of Object.keys(data)) {
    const option = document.createElement('option');
    option.value = departmentName;
    option.textContent = departmentName;
    departmentSelect.appendChild(option);
  }
}


/**
 * Rellena un `<select>` con opciones de clases basadas en el departamento seleccionado.
 * La función toma un objeto `data` donde las claves son los nombres de los departamentos y los valores son arrays de clases asociadas a esos departamentos.
 * Si no se selecciona un departamento o no hay clases disponibles para el departamento seleccionado, el select de clases se deshabilita.
 * Si hay clases disponibles para el departamento seleccionado, se habilita el select y se agregan las opciones correspondientes.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-12
 * @param {Object} data - Un objeto donde las claves son los nombres de los departamentos y los valores son arrays con los objetos de clases asociadas a esos departamentos.
 * @param {string} department - El nombre del departamento seleccionado, utilizado para acceder a las clases correspondientes en el objeto `data`.
 * @param {HTMLSelectElement} classSelect - El elemento `<select>` donde se agregarán las opciones de clases.
 * @returns {void} - No retorna ningún valor. Modifica directamente el contenido del elemento `<select>`.
 */
// Función para llenar las opciones de las clases según el departamento seleccionado
static populateClasses(data,department, classSelect) {
  
  classSelect.innerHTML = '';
  // Limpiar las opciones anteriores del select de clases
  classSelect.innerHTML = '<option value="">Seleccione una clase</option>';

  // Si no hay un departamento seleccionado, deshabilitar el select de clases
  if (!department || !data[department]) {
    classSelect.disabled = true;
    return;
  }

  // Habilitar el select de clases
  classSelect.disabled = false;

  // Agregar las opciones de clases correspondientes al departamento
  const classes = data[department];
  classes.forEach(classItem => {
    const option = document.createElement('option');
    option.value = classItem.ClassID;
    option.textContent = classItem.ClassName;
    classSelect.appendChild(option);
  });
}


/**
 * Agrega opciones a cada fila de la tabla para las secciones de inscripción. 
 * Para cada sección, muestra la cantidad de plazas disponibles, un botón para agregar un video de presentación (si está disponible) 
 * y una opción de radio para seleccionar la sección de inscripción.
 * La función realiza solicitudes asincrónicas para obtener la cantidad de plazas disponibles y ocupadas, 
 * así como la URL del video de presentación para la sección.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-11
 * @param {string} tableId - El ID de la tabla donde se agregarán las opciones de cada sección.
 * @returns {void} - No retorna valor. Modifica directamente el contenido de la tabla agregando celdas con las opciones correspondientes.
 */
static addOptionsSectionEnrollment(tableId){
  // Selecciona la tabla por su ID
  const table = document.getElementById(tableId);
  if (!table) {
    console.error("La tabla no existe.");
    return;
  }

  // Selecciona todas las filas del cuerpo de la tabla
  const rows = table.querySelectorAll("tbody tr");

  rows.forEach( async (row) => {
    // Obténer todas las celdas de la fila actual
    const cells = row.querySelectorAll("td");
    const sectionId = cells[2].textContent.trim();
    
    //Obtenmos la cantidad de spots disponibles
    let spots =0;
    let cellSpots = null;
    let cellVideo = Cell.createCell("td",);

    //Agregamos el botón del video 
    //botón que despliga la modal para agregar video
    const buttonVideo = document.createElement("button");
    buttonVideo.classList.add("btn");
    buttonVideo.classList.add("btn-video");
   
    // Creamos la imagen y configuramos su fuente 
    const videoIcon = document.createElement("img");
    videoIcon.src = this.path + "assets/img/icons/add-video-icon.png";

    // Agregamos la imagen al botón
    buttonVideo.appendChild(videoIcon);

   
    try {
      const sectionIdParsed = parseInt(sectionId, 10);
      const spotsavailable = await this.getAvailableSpots(sectionIdParsed);
      const spotsTaken = await this.getStudentCountByClassSection(sectionIdParsed);
      const url = await this.getUrlPresentationVideo(sectionIdParsed);

      spots = parseInt(spotsavailable, 10) - parseInt(spotsTaken, 10);
      cellSpots = Cell.createCell("td",spots.toString());
      
    

    // Extraemos el VIDEO_ID (en este caso "PcP7UyoRYTU")
    let videoId = url.split('v=')[1].split('/')[0];

    // Construimos la URL para el iframe
    let iframeUrl = `https://www.youtube.com/embed/${videoId}?autoplay=0`;

      buttonVideo.addEventListener('click', (event)=>{
        event.preventDefault();
        const iframe = document.getElementById('youtubePlayer');
              iframe.src = iframeUrl;
             
        Modal.showModal('videoModal');
      });


      cellVideo.appendChild(buttonVideo);
    } catch (error) {
      console.error('Error fetching spots:', error);
    }
    

    //Celda que contendrá las opciones
    const cellOptions = Cell.createCell("td", "");
    

    const div = document.createElement('div');
    div.style.width = '100%';

    const input = document.createElement('input');
    input.type = "radio";
    input.classList.add('btn-check');
    input.name = "sectionToEnroll"
    input.id = 'btn-'+sectionId
    input.value = sectionId;

    const label = document.createElement('label');
    label.style.width ="100%";
    label.classList.add('btn');
    label.classList.add('btn-outline-primary');
    label.setAttribute('for','btn-'+sectionId);
    label.innerText = 'Agregar';

    div.appendChild(input);
    div.appendChild(label);
  

    cellOptions.appendChild(div);
    row.appendChild( cellVideo);
    row.appendChild(cellSpots);
    row.appendChild(cellOptions);
  }) // Agregamos las opciones a la fila
}

/**
 * Obtiene la cantidad de plazas disponibles para una sección de clase específica.
 * Realiza una solicitud POST a la API para obtener el número de plazas disponibles 
 * basándose en el ID de la sección de clase proporcionado.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-11
 * @param {number} class_section_id - El ID de la sección de clase para la cual se consultarán las plazas disponibles.
 * @returns {Promise<number|null>} - Retorna el número de plazas disponibles si la solicitud es exitosa, o `null` si ocurre un error.
 */

 static async getAvailableSpots(class_section_id){
    const data = {
      class_section_id :class_section_id
    };

  
    try {
      const response = await fetch(
        this.path +
          "api/post/enrollment/PostAvailableSpots.php",
        {  
          method: "POST",  
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );

      const responseData = await response.json();
      
    return responseData.available_spots;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }    
 }


/**
 * Obtiene la cantidad de estudiantes inscritos en una sección de clase específica.
 * Realiza una solicitud POST a la API para obtener el número de estudiantes registrados 
 * en una sección de clase basándose en el ID de la sección proporcionado.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-11
 * @param {number} class_section_id - El ID de la sección de clase para la cual se consultará el número de estudiantes inscritos.
 * @returns {Promise<number|null>} - Retorna el número de estudiantes inscritos si la solicitud es exitosa, o `null` si ocurre un error.
 */
 static async getStudentCountByClassSection(class_section_id){
  const data = {
    class_section_id :class_section_id
  };


  try {
    const response = await fetch(
      this.path +
        "api/post/enrollment/PostStudentCountByClassSection.php",
      { 
        method: "POST",  
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      }
    );

    const responseData = await response.json();
    

  return responseData.student_count;
  } catch (error) {
    console.error("Error:", error);
    return null;
  }    
}


/**
 * Inserta una matrícula en una sección de clase para un estudiante.
 * Realiza una solicitud POST a la API para registrar a un estudiante en una sección de clase específica.
 * Si la matrícula se encuentra en espera, muestra un modal con la opción de agregar el estudiante a la lista de espera.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-11
 * @param {number} student_id - El ID del estudiante que se quiere matricular en una sección de clase.
 * @param {number} class_section_id - El ID de la sección de clase en la que se quiere matricular al estudiante.
 * @returns {Promise<object|null>} - Retorna el objeto de respuesta de la API con el estado de la matrícula si es exitosa, o `null` si ocurre un error.
 */

 static async insertEnrollmentClassSection(student_id, class_section_id){
    const data = {
      student_id :student_id,
      class_section_id:class_section_id
    };


    try {
      const response = await fetch(
        this.path +
          "api/post/enrollment/PostInsertEnrollmentClassSection.php",
        {   
          method: "POST",  
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );

      const responseData = await response.json();
      

    
      if (responseData.status != "success") {
        Alert.display(
          responseData.status,
          "oh",
           responseData.message,
          this.path
        )

        if(responseData.message.includes('Seccion en Espera')){
               const body = document.querySelector('#warningModal .modal-body');
                const footer = document.querySelector('#warningModal .modal-footer');
                const warningModalLabel = document.getElementById('warningModalLabel');
                warningModalLabel.innerText = "";
                warningModalLabel.innerText = "Sección en espera";
                // Limpiar contenido existente
                body.innerHTML = '';
                footer.innerHTML = '';
            
                // Crear el contenedor centralizado
                const centeredContainer = document.createElement('div');
                centeredContainer.className = 'd-flex flex-column justify-content-center align-items-center text-center';
            
                // Crear y agregar imagen con animación
                const imgContainer = document.createElement('div');
                imgContainer.className = 'mb-4';
            
                const img = document.createElement('img');
                img.src = this.path+'assets/img/icons/sand-clock-icon.png';
                img.alt = '';
                img.className = 'sand-clock';
                imgContainer.appendChild(img);
            
                // Crear y agregar título
                const title = document.createElement('p');
                title.className = 'fs-4';
                title.textContent =  'Sección se encuentra en espera';

                const highlight = document.createElement('div');
                highlight.className = 'highlight mx-auto';
                highlight.textContent = responseData.message;
          
            
                // Crear y agregar párrafo de información adicional
                const infoParagraph = document.createElement('p');
                infoParagraph.className = 'mt-4';
                infoParagraph.innerHTML = `
                    Si deseas matrícular aún así la sección, presiona el botón "Matrícular en espera"
                `;
            
                // Agregar todos los elementos al contenedor centralizado
                centeredContainer.appendChild(imgContainer);
                centeredContainer.appendChild(title);
                centeredContainer.appendChild( highlight);
                centeredContainer.appendChild(infoParagraph);
               
                // Agregar el contenedor al cuerpo del modal
                body.appendChild(centeredContainer);
              
                const button = document.createElement('button');
                button.classList.add('btn');
                button.classList.add('btn-warning');
                button.textContent = 'Matrícular en espera'
                button.addEventListener('click', ()=>{
                  //Llamar a la función de espera
              
                  this.insertWaitingListClassSection(student_id,parseInt(class_section_id,10));
                })

                footer.appendChild(button);
                // Mostrar la modal
                Modal.showModal('warningModal');
            
        }
      } else{
        Alert.display(
          responseData.status,
          "Enhorabuena",
          responseData.message,
          this.path
        )
      }
    
    return responseData;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }    
 }

 /**
 * Elimina una matrícula de un estudiante en una sección de clase.
 * Realiza una solicitud POST a la API para eliminar la matrícula del estudiante en una sección de clase específica.
 * Muestra un mensaje de éxito o error dependiendo del resultado de la solicitud.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-11
 * @param {number} student_id - El ID del estudiante cuya matrícula se desea eliminar.
 * @param {number} class_section_id - El ID de la sección de clase de la cual se desea eliminar al estudiante.
 * @returns {Promise<object|null>} - Retorna el objeto de respuesta de la API con el estado de la eliminación si es exitosa, o `null` si ocurre un error.
 */
  static async deleteEnrollmentStudent(student_id,class_section_id){
    const data = {
      student_id :student_id,
      class_section_id:class_section_id
    };


    try {
      const response = await fetch(
        this.path +
          "api/post/enrollment/PostDeleteEnrollmentStudent.php",
        {   
          method: "POST",  
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );

      const responseData = await response.json();
      

      console.log(response);
      if (responseData.status != "success") {
        Alert.display(
          responseData.status,
          "oh",
           responseData.message,
          this.path
        )
      } else{
        Alert.display(
          responseData.status,
          "Enhorabuena",
          responseData.message,
          this.path
        )
      }
    console.log(responseData);  
    return responseData;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }    
  }


    /**
   * Agrega una columna de opciones a una tabla específica, permitiendo gestionar las filas mediante botones.
   * Actualmente solo se agrega la función de eliminar dicha sección.
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-12-09
   * @param {string} tableId - El ID de la tabla a la que se agregarán las opciones.
   * @returns {void} No retorna ningún valor.
   */

    static addOptionTableMain(tableId, student_id) {
      // Selecciona la tabla por su ID
      const table = document.getElementById(tableId);
      if (!table) {
        console.error("La tabla no existe.");
        return;
      }
  
      // Selecciona todas las filas del cuerpo de la tabla
      const rows = table.querySelectorAll("tbody tr");
  
      rows.forEach((row) => {
        // Obténer todas las celdas de la fila actual
        const cells = row.querySelectorAll("td");
        const sectionId = cells[2].textContent.trim();
  
        //Celda que contendrá las opciones
        const cellOptions = Cell.createCell("td", "");
  
        //botón que elima la clase si estamos en proceso de matricula
        const buttonDelete = document.createElement("button");
        buttonDelete.classList.add("btn");
        buttonDelete.classList.add("btn-danger");
        buttonDelete.classList.add("deleteButton");
        buttonDelete.setAttribute("section", sectionId);
        buttonDelete.innerText = "Eliminar";
  
        buttonDelete.addEventListener("click", async () => {
          buttonDelete.disabled =true;
         await this.deleteEnrollmentStudent(student_id,parseInt(sectionId,10));
         setTimeout(()=>{
          location.reload(true);
         },6000);
        
        });
  
        cellOptions.appendChild(buttonDelete);
  
        row.appendChild(cellOptions); // Agregamos las opciones a la fila
      });
    }

    /**
 * Inserta un estudiante en la lista de espera para una sección de clase.
 * Realiza una solicitud POST a la API para agregar al estudiante en la lista de espera de una sección de clase.
 * Muestra un mensaje de éxito o error dependiendo del resultado de la solicitud.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-11
 * @param {number} student_id - El ID del estudiante que desea ser agregado a la lista de espera.
 * @param {number} class_section_id - El ID de la sección de clase a la cual el estudiante será agregado a la lista de espera.
 * @returns {Promise<object|null>} - Retorna el objeto de respuesta de la API con el estado de la operación si es exitosa, o `null` si ocurre un error.
 */
    static  async insertWaitingListClassSection(student_id,class_section_id){

      const data = {
        student_id :student_id,
        class_section_id:class_section_id
      };
  
  
      try {
        const response = await fetch(
          this.path +
            "api/post/enrollment/PostInsertWaitingListClassSection.php",
          {  
            method: "POST",  
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
          }
        );
  
        const responseData = await response.json();
        
  
      
          if (responseData.status != "success") {
            Alert.display(
              responseData.status,
              "oh",
              responseData.message,
              this.path
            )
          } else{
            Alert.display(
              responseData.status,
              "Enhorabuena",
              responseData.message,
              this.path
            )
          }
        console.log(responseData);  
        return responseData;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }   
  
  }
  /**
 * Obtiene la URL del video de presentación para una sección de clase específica.
 * Realiza una solicitud POST a la API para obtener la URL del video asociado a una clase.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-11
 * @param {number} idClassSection - El ID de la sección de clase para la que se desea obtener la URL del video de presentación.
 * @returns {Promise<string|null>} - Retorna la URL del video de presentación si es exitosa, o `null` si ocurre un error.
 */
  static async getUrlPresentationVideo(idClassSection){
    const data = {
      idClassSection :idClassSection
    };
  
  
    try {
      const response = await fetch(
        this.path +
          "api/post/academicPlanning/PostUrlPresentationVideo.php",
        { 
          method: "POST",  
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );
  
      const responseData = await response.json();
      
    
    return responseData.urlVideo;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }    
  }


 

}


export {EnrollmentProcess};