import { AcademicPlanning } from "./AcademicPlanning.mjs";
import { Alert } from "../behavior/support.mjs";

class Professor {
  static path = "../../../../";
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
        "../../../.././"
      );
      console.error("No se encontraron profesores o los datos no son válidos.");
    }
  }

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

  static async setUrlVideoClassSection(idProfessor, urlVideo, idClassSection) {
    const data = {
        idProfessor:idProfessor,
        urlVideo:urlVideo,
        idClassSection:idClassSection
    };

    console.log(data);
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
      console.log("Respuesta del servidor:", responseData);

      if (responseData.status == "success") {
        Alert.display(
          responseData.status,
          "Enhorabuena",
          responseData.message,
          "../../../../"
        );
      } else {
        Alert.display(
          responseData.status,
          "oh",
          responseData.message,
          "../../../../"
        );
      }

      return responseData.status;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }
  }

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



}

export { Professor };
