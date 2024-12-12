import { Alert, Cell, Modal } from "../behavior/support.mjs";

class EnrollmentProcess {
  static path = "../../../../";viewSections

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
          'Verifica las selección',
          this.path
        )
      } 
      
     return responseData;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }    
  } 


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
          'Verifica las selección',
          this.path
        )
      } 

     return responseData.data;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }    
  } 


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
          'Algo anda mal',
          this.path
        )
      } 
   
     return responseData.data;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }    
  } 



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