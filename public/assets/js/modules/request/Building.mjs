import { AcademicPlanning } from "./AcademicPlanning.mjs";
import { Alert } from "../behavior/support.mjs";
import { Classroom } from "./Classroom.mjs";

class Building {
  /**
   * Renderiza un `<select>` con las opciones de edificios correspondientes a un centro regional específico.
   * Los edificios se obtienen desde una fuente de datos a través de la función `getDataInfrastructureAcademicPlanning`.
   * Si los edificios están disponibles, se agregan al `<select>` y se marca el primero como seleccionado.
   * Además, se actualizan las aulas disponibles para el edificio seleccionado utilizando `renderSelectClassroomsByCenter`.
   * Si no se encuentran edificios o los datos no son válidos, se muestra un mensaje de error.
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-11-27
   * @param {string} idSelect - El ID del elemento `<select>` donde se renderizarán los edificios.
   * @param {int} username_user_professor - El nombre de usuario del profesor para recuperar la planificación académica.
   * @param {int} id_regionalcenter - El ID del centro regional para filtrar los edificios.
   * @returns {Promise<void>} - No retorna valor. Realiza una solicitud asíncrona para obtener los edificios y actualiza el contenido del `<select>`.
   */

  static async renderSelectBuildingsByCenter(
    idSelect,
    username_user_professor,
    id_regionalcenter
  ) {
    const select = document.getElementById(idSelect);
    select.innerHTML = "";

    //Eliminamos el contenido que pueda tener el select de carrera principal
    select.innerHTML = "";

    const buildings =
      await AcademicPlanning.getDataInfrastructureAcademicPlanning(
        username_user_professor,
        id_regionalcenter
      );

    // Comprobamos que tenemos datos antes de intentar renderizarlos
    if (buildings && Array.isArray(buildings)) {
      const addedIds = new Set();

      buildings.forEach((building) => {
        if (!addedIds.has(building.id_building)) {
          // Verifica si el ID ya fue agregado
          const option = document.createElement("option");
          option.value = building.id_building;
          option.innerText = building.name_building;

          if (addedIds.size === 0) {
            // Si es la primera opción, seleccionarla
            option.selected = true;
            Classroom.renderSelectClassroomsByCenter(
              "classroom",
              username_user_professor,
              id_regionalcenter,
              building.id_building
            );
          }

          select.appendChild(option);
          addedIds.add(building.id_building); // Agrega el ID al Set
        }
      });
    } else {
      Alert.display(
        "error",
        "Lo sentimos",
        "No se encontraron Edificios",
        "../../../.././"
      );
      console.error("No se encontraron Edificios o los datos no son válidos.");
    }
  }
}

export { Building };
