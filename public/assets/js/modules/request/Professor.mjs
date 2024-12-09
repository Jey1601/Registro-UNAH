import { AcademicPlanning } from "./AcademicPlanning.mjs";
import { Alert } from "../behavior/support.mjs";

class Professor {
    static path ='../../../../'
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
      console.log(data);
      return data.assignedClasses;
    } catch (error) {
      return [];
    }
  }
}

export { Professor };
