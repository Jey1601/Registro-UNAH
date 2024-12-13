import { Career } from "./Career.mjs";
import { Alert } from "../behavior/support.mjs";
import { AcademicPlanning } from "./AcademicPlanning.mjs";
import { Building } from "./Building.mjs";
class RegionalCenter {
  static path = "../../../../";

  /**
 * Renderiza un select con los centros regionales disponibles, cargando sus opciones en un dropdown.
 * Si el módulo es "Admissions", también carga las carreras asociadas al centro regional seleccionado.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-11
 * 
 * @param {string} id - El ID del elemento `<select>` donde se agregarán las opciones de centros regionales.
 * @param {string} [module="Admissions"] - El módulo para el que se están cargando los centros. Si es "Admissions", se actualizan las carreras asociadas.
 */
  static async renderSelectRegionalCenters(id, module = "Admissions") {
    const select = document.getElementById(id);
    select.innerHTML = "";

    const regionalCenters = await this.getRegionalCenters();

    // Comprobamos que tenemos datos antes de intentar renderizarlos
    if (regionalCenters && Array.isArray(regionalCenters)) {
      let counter = 0;
      regionalCenters.forEach((center) => {
        const option = document.createElement("option");
        option.value = center.id_regional_center;
        option.innerText = center.name_regional_center;

        if (counter == 0) {
          option.selected = true;
        }

        select.appendChild(option);

        counter++;
      });

      if (module == "Admissions") {
        //Cargamos las carreras del centro regional escogido
        select.addEventListener("change", function () {
          Career.updateFirstOption();
        });
        // pero también se llama una vez por el valor de defecto
        Career.updateFirstOption();
      }
    } else {
      console.error(
        "No se encontraron centros regionales o los datos no son válidos."
      );
    }
  }

  /**
 * Obtiene la lista de centros regionales desde el servidor.
 * Realiza una solicitud GET al endpoint de la API y devuelve los centros regionales en formato JSON.
 * Si ocurre un error, devuelve un array vacío.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-11
 * 
 * @returns {Array} - Un array de objetos que representan los centros regionales. Si hay un error, devuelve un array vacío.
 */
  // Método para obtener los centros regionales de la API
  static async getRegionalCenters() {
    try {
      const response = await fetch(
        this.path + "api/get/regionalCenter/regionalCenters.php"
      );

      if (!response.ok) {
        throw new Error("Error en la solicitud: " + response.status);
      }

      const data = await response.json();

      return data; // Retorna los centros regionales
    } catch (error) {
      return []; // Si hay un error, retornamos un array vacío
    }
  }


  /**
 * Renderiza un select de centros regionales basado en el departamento seleccionado.
 * Esta función toma el valor del select de departamento, obtiene los centros regionales correspondientes
 * a ese departamento y los muestra en un select de centros regionales.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-11
 * 
 * @param {string} idSelect - El ID del elemento select donde se mostrarán los centros regionales.
 * @param {string} idDepartmentSelect - El ID del elemento select donde se selecciona el departamento.
 */
  static async renderSelectRegionalCentersByDepartment(
    idSelect,
    idDepartmentSelect
  ) {
    const select = document.getElementById(idSelect);
    select.innerHTML = "";

    //Select de centro regional
    const departmentSelect = document.getElementById(idDepartmentSelect);

    //Tomamos el valor seleccionado del select de centro regional
    const selectedValue = departmentSelect.value;
    
    //Eliminamos el contenido que pueda tener el select de carrera principal
    select.innerHTML = "";

    const regionalCenters = await this.getRegionalCentersByDepartment(
      selectedValue
    );

    // Comprobamos que tenemos datos antes de intentar renderizarlos
    if (regionalCenters && Array.isArray(regionalCenters)) {
      let counter = 0;
      regionalCenters.forEach((center) => {
        const option = document.createElement("option");
        option.value = center.id_regional_center;
        option.innerText = center.name_regional_center;

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
        "No se encontraron centros regionales",
        "../../../.././"
      );
     
    }
  }



  /**
 * Obtiene los centros regionales asociados a un departamento específico.
 * Esta función realiza una solicitud al servidor para obtener los centros regionales
 * correspondientes al ID del departamento proporcionado.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-11
 * 
 * @param {int} idDepartment - El ID del departamento cuyo centros regionales se desean obtener.
 * @returns {Array} Un arreglo de centros regionales si la solicitud es exitosa, o un arreglo vacío si ocurre un error.
 */
  static async getRegionalCentersByDepartment(idDepartment) {
    try {
      const response = await fetch(
        this.path +
          `/api/get/regionalCenter/regionalCentersByDepartment.php?id_department=${idDepartment}`
      );

      if (!response.ok) {
        throw new Error("Error en la solicitud: " + response.status);
      }

      const data = await response.json();

      return data; // Retorna los departamentos
    } catch (error) {
      return []; // Si hay un error, retornamos un array vacío
    }
  }


  /**
 * Renderiza un select de centros regionales específicos de un profesor.
 * Esta función obtiene los centros regionales asociados a un profesor y los renderiza
 * en un select HTML. Además, se renderizan los select de carreras y edificios asociados
 * al centro regional seleccionado.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12
 * 
 * @param {string} idSelect - El ID del select donde se renderizarán los centros regionales.
 * @param {int} idProfessor - El ID del profesor cuyo centros regionales se desean obtener.
 */
  static async renderSelectRegionalCentersByProfessor(idSelect, idProfessor) {
    const select = document.getElementById(idSelect);
    select.innerHTML = "";

    //Eliminamos el contenido que pueda tener el select de carrera principal
    select.innerHTML = "";

    const regionalCenters =
      await AcademicPlanning.regionalCentersAcademicPlanning(idProfessor);
    
    // Comprobamos que tenemos datos antes de intentar renderizarlos
    if (regionalCenters && Array.isArray(regionalCenters)) {
      let counter = 0;
      regionalCenters.forEach((center) => {
        const option = document.createElement("option");
        option.value = center.id_regionalcenter;
        option.innerText = center.name_regional_center;

        if (counter == 0) {
          option.selected = true;
          Career.renderSelectUndergraduatesByCenter(
            "academicPlannigUndegraduate",
            idProfessor,
            center.id_regionalcenter
          );
          Building.renderSelectBuildingsByCenter(
            "building",
            idProfessor,
            center.id_regionalcenter
          );
        }

        select.appendChild(option);

        counter++;
      });

      this.renderCenterFilters(regionalCenters);
    } else {
      Alert.display(
        "error",
        "Lo sentimos",
        "No se encontraron centros regionales",
        "../../../.././"
      );
      console.error(
        "No se encontraron centros regionales o los datos no son válidos."
      );
    }
  }

  /**
 * Renderiza los filtros de centros regionales como botones de opción (radio buttons).
 * Crea dinámicamente los botones de opción para cada centro regional, permitiendo 
 * seleccionar un centro regional de manera visual y fácil.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-12
 * 
 * @param {Array} regionalCenters - Lista de centros regionales que se deben mostrar como filtros.
 * Cada objeto debe tener propiedades como `id_regionalcenter` y `name_regional_center`.
 */
  static renderCenterFilters(regionalCenters){
    const container = document.getElementById("regionalCenterFilter");
    container.innerHTML = "";
  // Crear los elementos dinámicamente
  regionalCenters.forEach((center, index) => {
    const radioId = `btnradio${center.id_regionalcenter}`;

    // Crear el input del radio button
    const input = document.createElement("input");
    input.type = "radio";
    input.className = "btn-check";
    input.name = "btnradio";
    input.value = center.id_regionalcenter;
    input.id = radioId;
    input.autocomplete = "off";
    if (index === 0) input.checked = true; // Marcar el primero por defecto

    // Crear la etiqueta del radio button
    const label = document.createElement("label");
    label.className = "btn btn-outline-primary";
    label.htmlFor = radioId;
    label.textContent = center.name_regional_center;

    // Agregar el input y la etiqueta al contenedor
    container.appendChild(input);
    container.appendChild(label);
  });
  }
}

export { RegionalCenter };
