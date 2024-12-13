import { AcademicPlanning } from "./AcademicPlanning.mjs";
import { Alert, Cell, Table, Modal } from "../behavior/support.mjs";

class Professor {
  static path = "../../../../";

  /**
 * Renderiza un `select` de profesores disponibles basados en los criterios de planificación académica.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-03
 * 
 * @param {string} idSelect - El ID del elemento `select` donde se agregarán las opciones de profesores.
 * @param {int} regionalCenter - El centro regional para filtrar los profesores.
 * @param {int} username_user_professor - El nombre de usuario del profesor para filtrar según el plan académico.
 * @param {string} days - Los días de la semana en los que se realiza la clase, utilizados para filtrar los profesores.
 * @param {string} startTime - La hora de inicio de la clase, usada para filtrar los profesores disponibles en ese horario.
 * @param {string} endTime - La hora de finalización de la clase, usada para filtrar los profesores disponibles en ese horario.
 * @returns {Promise<void>} - Esta función no retorna nada, solo modifica el contenido del `select` de profesores.
 */

  static async renderSelectProfessors(
    idSelect,
    regionalCenter,
    username_user_professor,
    days,
    startTime,
    endTime
  ) {
    const select = document.getElementById(idSelect);
    select.innerHTML = "";

    //Eliminamos el contenido que pueda tener el select de carrera principal
    select.innerHTML = "";

    const professors = await AcademicPlanning.getDataProfessorsAcademicPlanning(
      regionalCenter,
      username_user_professor,
      days,
      startTime,
      endTime
    );

    // Comprobamos que tenemos datos antes de intentar renderizarlos
    if (professors && Array.isArray(professors)) {
      let counter = 0;
      professors.forEach((professor) => {
        const option = document.createElement("option");
        option.value = professor.id_professor;
        option.innerText = `${professor.id_professor}-${professor.first_name} - ${professor.first_lastname}`;
        option.setAttribute("id_professor", professor.id_professor);

        if (counter == 0) {
          option.selected = true;
        }

        select.appendChild(option);

        counter++;
      });
    } else {
      Alert.display(
        "error",
        "Lo sentimos",
        "No se encontraron profesores ",
        this.path
      );
      console.error("No se encontraron profesores o los datos no son válidos.");
    }
  }


  /**
 * Obtiene las clases asignadas a un profesor específico.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-10
 * 
 * @param {int} idProfessor - El ID del profesor para obtener las clases asignadas.
 * @returns {Promise<Array>} - Un arreglo con las clases asignadas al profesor. Si ocurre un error, retorna un arreglo vacío.
 */

  static async getAssignedClasses(idProfessor) {
    try {
      const response = await fetch(
        this.path +
          `/api/get/professor/getAssignedClasses.php?idProfessor=${idProfessor}`
      );

      if (!response.ok) {
        throw new Error("Error en la solicitud: " + response.status);
      }

      const data = await response.json();
      
      return data.assignedClasses;
    } catch (error) {
      return [];
    }
  }

  /**
 * Establece la URL del video para una sección de clase específica de un profesor.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-09
 * 
 * @param {int} idProfessor - El ID del profesor que está asignado a la sección.
 * @param {string} urlVideo - La URL del video que se quiere asignar a la sección de clase.
 * @param {int} idClassSection - El ID de la sección de clase a la que se le asignará el video.
 * @returns {Promise<string|null>} - El estado de la operación ("success" si todo fue bien, o null si ocurrió un error).
 */

  static async setUrlVideoClassSection(idProfessor, urlVideo, idClassSection) {
    const data = {
        idProfessor:idProfessor,
        urlVideo:urlVideo,
        idClassSection:idClassSection
    };

    
    try {
      const response = await fetch(
        this.path +
          "api/put/professor/setUrlVideoClassSection.php",
        {  
          method: "PUT",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );

      const responseData = await response.json();
    

      if (responseData.status == "success") {
        Alert.display(
          responseData.status,
          "Enhorabuena",
          responseData.message,
          this.path
        );
      } else {
        Alert.display(
          responseData.status,
          "oh",
          responseData.message,
         this.path
        );
      }

      return responseData.status;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }
  }


  /**
 * Obtiene los estudiantes de una sección de clase y descarga la información en un archivo CSV.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-10
 * 
 * @param {int} idSectionClass - El ID de la sección de clase de la cual se quieren obtener los estudiantes.
 * @returns {Promise<void>} - No retorna ningún valor. Si se realiza correctamente, descarga un archivo CSV con los estudiantes.
 */
  static async getStudentsBySectionCSV(idSectionClass) {
    try {
      const response = await fetch(
        this.path +
          `api/get/professor/getStudentsBySectionCSV.php?idSectionClass=${idSectionClass}`,
        {
          method: "GET",
          headers: {
            Accept: "text/csv",
          },
        }
      );
  
      if (!response.ok) {
        throw new Error("Error en la solicitud: " + response.status);
      }
  
      const contentType = response.headers.get("Content-Type");
  
      if (contentType && contentType.includes("text/csv")) {
 
        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
  
        const a = document.createElement("a");
        a.href = url;
        a.download = "EstudiantesSección.csv"; 
        document.body.appendChild(a);
        a.click();
  
   
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
      } else if (contentType && contentType.includes("application/json")) {
   
        const data = await response.json();
        Alert.display("error", "Oh", data.message || "Sección vacía", this.path);
      } else {
        throw new Error("Respuesta inesperada del servidor");
      }
    } catch (error) {
      console.error("Error al manejar la solicitud:", error);
      Alert.display("error", "Oh", "Ocurrió un error al procesar la solicitud.", this.path);
    }
  }

/**
 * Obtiene los estudiantes de una sección de clase y descarga la información en un archivo PDF.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-10
 * 
 * @param {int} idSectionClass - El ID de la sección de clase de la cual se quieren obtener los estudiantes.
 * @returns {Promise<void>} - No retorna ningún valor. Si se realiza correctamente, descarga un archivo PDF con los estudiantes.
 */
  static async getStudentsBySectionPDF(idSectionClass) {
    try {
      const response = await fetch(
        this.path +
          `api/get/professor/getStudentsBySectionPDF.php?idSectionClass=${idSectionClass}`,
        {
          method: "GET",
          headers: {
            Accept: "application/pdf",
          },
        }
      );
  
      if (!response.ok) {
        throw new Error("Error en la solicitud: " + response.status);
      }
  
     
      const contentType = response.headers.get("Content-Type");
  
      if (contentType && contentType.includes("application/pdf")) {
      
        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
  
        const a = document.createElement("a");
        a.href = url;
        a.download = "Sección_Estudiantes.pdf"; 
        document.body.appendChild(a);
        a.click();
  
      
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
      } else if (contentType && contentType.includes("application/json")) {
       
        const data = await response.json();
        Alert.display('error', 'Oh', data.message || 'Sección vacía', this.path);
      } else {
        throw new Error("Respuesta inesperada del servidor");
      }
    } catch (error) {
      console.error("Error al manejar la solicitud:", error);
      Alert.display('error', 'Oh', 'Ocurrió un error al procesar la solicitud.', this.path);
    }
  }


  /**
 * Obtiene la carga académica de un profesor mediante una solicitud POST.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-10
 * 
 * @param {int} idProfessor - El ID del profesor para obtener su carga académica.
 * @returns {Promise<Array>} - Devuelve una lista con la carga académica del profesor, si la solicitud es exitosa. Si ocurre un error, devuelve un arreglo vacío.
 */
  static async getAcademicCharge(idProfessor) {
    const data = {
        idProfessor: idProfessor,  // El valor que se va a enviar
    };

   
    try {
        // Realiza la solicitud POST
        const response = await fetch(
            this.path + "api/post/professor/getAcademicCharge.php",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",  
                },
                body: JSON.stringify(data), 
            }
        );


        const responseData = await response.json();
      
 
        if (responseData.status == 'success') {
            Alert.display(
                responseData.status,
                "Enhorabuena",
                responseData.message,
                this.path
            );
        } else {
            Alert.display(
                "warning",
                "Oh",
                responseData.message,
                this.path
            );
        }
        
        return responseData.academicCharge;
    } catch (error) {
        console.error("Error:", error);
        return [];
    }
}

/**
 * Obtiene la carga académica en formato CSV mediante una solicitud POST.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-10
 * 
 * @param {int} idProfessor - El ID del profesor para obtener su carga académica en formato CSV.
 * @returns {Promise<void>} - No devuelve ningún valor, pero si la solicitud es exitosa, descarga un archivo CSV. Si ocurre un error, muestra un mensaje de alerta.
 */
static async getAcademicChargeCSV(idProfessor) {
  const data = { idProfessor: parseInt(idProfessor, 10) };

  try {
    const response = await fetch(this.path + 'api/post/professor/getAcademicChargeCSV.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    });

    // Verificar si la respuesta es un archivo CSV
    const contentType = response.headers.get('Content-Type');
    
    if (contentType && contentType.includes('text/csv')) {
      const blob = await response.blob(); 
      const link = document.createElement('a');
      link.href = URL.createObjectURL(blob); 
      link.download = 'academic_charge.csv'; 
      link.click(); 
    } else {
      
      const responseData = await response.json(); 
      Alert.display('error: ', 'oh', 'Aún no hay datos de carga', this.path);
    }
  } catch (error) {
    console.error('Error en la solicitud:', error);
  }
}

/**
 * Obtiene la carga académica en formato PDF mediante una solicitud POST.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-12
 * 
 * @param {int} idProfessor - El ID del profesor para obtener su carga académica en formato PDF.
 * @returns {Promise<void>} - No devuelve ningún valor, pero si la solicitud es exitosa, descarga un archivo PDF. Si ocurre un error, muestra un mensaje de alerta.
 */
static async getAcademicChargePDF(idProfessor) {
  const data = { idProfessor: parseInt(idProfessor, 10) };

  try {
    const response = await fetch(this.path + 'api/post/professor/getAcademicChargePDF.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    });

   
    const contentType = response.headers.get('Content-Type');
    
    if (contentType && contentType.includes('application/pdf')) {
      const blob = await response.blob();
      const link = document.createElement('a'); 
      link.href = URL.createObjectURL(blob); 
      link.download = 'academic_charge.pdf'; 
      link.click(); 
    } else {
      
      const responseData = await response.json(); 
      Alert.display('error: ', 'oh', 'Aún no hay datos de carga', this.path);
    }
  } catch (error) {
    console.error('Error en la solicitud:', error);
  }
}


/**
 * Obtiene las solicitudes de cancelación excepcional para un profesor.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-12
 * 
 * @param {int} idProfessor - El ID del profesor para obtener las solicitudes de cancelación excepcional.
 * @returns {Promise<Array>} - Devuelve un array con las solicitudes de cancelación excepcional del profesor, o un array vacío en caso de error o sin resultados.
 */
static async getRequestsCancellationExceptional(idProfessor) {
  const data = { idProfessor: parseInt(idProfessor, 10) };

  try {
    const response = await fetch(this.path + 'api/post/professor/getRequestsCancellationExceptional.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json', 
      },
      body: JSON.stringify(data), 
    });


    const responseData = await response.json();

    if (responseData.success) {
      console.log('Respuesta:', responseData); 
    } else {
      console.error('Error:', responseData.message); 
    }


    return responseData.requestsExceptionalCancellation;

   
  } catch (error) {
    console.error('Error en la solicitud:', error); 
  }
}

/**
 * Obtiene los estudiantes de un centro regional y carrera de pregrado.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-11
 * 
 * @param {int} idProfessor - El ID del profesor.
 * @param {int} idRegionalCenter - El ID del centro regional.
 * @param {int} idUndergraduate - El ID de la carrera de pregrado.
 * @returns {Promise<Array>} - Devuelve un array con los estudiantes que cumplen con los criterios, o un array vacío en caso de error o sin resultados.
 */
static async  fetchStudentsByRegionalCenterUndergraduate(idProfessor,idRegionalCenter,idUndergraduate) {
  const url = this.path+'api/post/professor/getStudentsByRegionalCenterUndergraduate.php'; 

  const formData = new FormData();
  formData.append('idProfessor', parseInt(idProfessor,10)); 
  formData.append('idRegionalCenter', parseInt(idRegionalCenter,10)); 
  formData.append('idUndergraduate', parseInt(idUndergraduate,10)); 

  try {
      const response = await fetch(url, {
          method: 'POST',
          body: formData,
      });

      if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
      }

      const data = await response.json();
      console.log(data);

      return data.students;
  } catch (error) {
      console.error('Error al consumir el endpoint:', error);
      return [];
  }
}

/**
 * Obtiene el historial académico de un estudiante
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-10
 * 
 * @param {int} idProfessor - El ID del profesor.
 * @param {int} idStudent - El ID del estudiante.
 * @returns {Promise<Array>} - Devuelve un array con las secciones del historial académico del estudiante, o un array vacío en caso de error o sin resultados.
 */
static async  getAcademicHistoryByStudent(idProfessor, idStudent) {
  const url = this.path+'api/post/professor/getAcademicHistoryByStudent.php'; 

  const formData = new FormData();
  formData.append('idProfessor', parseInt(idProfessor,10)); 
  formData.append('idStudent', parseInt(idStudent,10)); 


  try {
      const response = await fetch(url, {
          method: 'POST',
          body: formData,
      });

      if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
      }

      const data = await response.json();
      console.log(data);
      Alert.display(data.status,'Hola',data.message, this.path);
      return data.secciones;
  } catch (error) {
      console.error('Error al consumir el endpoint:', error);
      return [];
  }
}

/**
 * Agrega un botón de vista previa del historial académico a cada fila de la tabla de calificaciones.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12-11
 * 
 * @param {string} tableId - El ID de la tabla donde se agregarán los botones.
 * @param {int} idProfessor - El ID del profesor para consultar el historial académico del estudiante.
 */

static addOptionGrades(tableId,idProfessor){
     // Selecciona la tabla por su ID
     const table = document.getElementById(tableId);
     if (!table) {
       console.error("La tabla no existe.");
       return;
     }
 
     // Selecciona todas las filas del cuerpo de la tabla
     const rows = table.querySelectorAll("tbody tr");
 
     rows.forEach((row) => {
       // Obtén todas las celdas de la fila actual
       const cells = row.querySelectorAll("td");
    
       const idStudent = cells[0].textContent.trim();
 
       //Celda que contendrá las opciones
       const cellOptions = Cell.createCell("td", "");
 
    
          //Botón de descarga PDF
          const buttonView = document.createElement("button");
          buttonView.classList.add("btn");
      
          buttonView.addEventListener('click',async ()=>{
            //Aquí llamar la función de motrar historial individual
            const sections =  await this.getAcademicHistoryByStudent(parseInt(idProfessor,10),idStudent)
            Table.renderDynamicTable(sections,'viewHistorial');
            Modal.showModal('viewInfo');
          })
    
          // Creamos la imagen y configuramos su fuente
          const icon = document.createElement("img");
          icon.src = this.path + "assets/img/icons/zoom-icon.png";
 
    
          buttonView.appendChild(icon);
 
          cellOptions.appendChild(buttonView);
      
 
       row.appendChild(cellOptions); // Agregamos las opciones a la fila
     });
}

}

export { Professor };
