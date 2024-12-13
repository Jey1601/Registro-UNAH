import { AcademicPlanning } from "./AcademicPlanning.mjs";
import { Alert } from "../behavior/support.mjs";
class Schedule {
  /**
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-11
   */
  static async renderSelectSchedules(idSelect) {
    const select = document.getElementById(idSelect);
    select.innerHTML = "";

    //Eliminamos el contenido que pueda tener el select de carrera principal
    select.innerHTML = "";

    const schedules =
      await AcademicPlanning.getDataAcademicSchedulesAcademicPlanning();

    // Comprobamos que tenemos datos antes de intentar renderizarlos
    if (schedules && Array.isArray(schedules)) {
      let counter = 0;
      schedules.forEach((schedule) => {
        const option = document.createElement("option");
        option.value = schedule.id_academic_schedule;
        option.innerText = `${schedule.start_time} - ${schedule.end_time}`;
        option.setAttribute("start_time", schedule.start_time);
        option.setAttribute("end_time", schedule.end_time);
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
        "No se encontraron horarios ",
        "../../../.././"
      );
      console.error("No se encontraron horarios o los datos no son válidos.");
    }
  }

  /**
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-11
   */
  static getSelectedDays() {
    // Selecciona todos los inputs de tipo checkbox con la clase "btn-check"
    const checkboxes = document.querySelectorAll("#planningForm .btn-check");
    // Filtra los checkbox seleccionados y mapea sus valores
    const selectedDays = Array.from(checkboxes)
      .filter((checkbox) => checkbox.checked) // Filtra los seleccionados
      .map((checkbox) => checkbox.value); // Obtiene los valores

    return selectedDays;
  }
}

export { Schedule };
