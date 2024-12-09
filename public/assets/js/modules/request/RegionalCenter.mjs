import { Career } from "./Career.mjs";
import { Alert } from "../behavior/support.mjs";
import { AcademicPlanning } from "./AcademicPlanning.mjs";
import { Building } from "./Building.mjs";
class RegionalCenter {
  static path = "../../../../";
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
    console.log(selectedValue);
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
      console.error(
        "No se encontraron centros regionales o los datos no son válidos."
      );
    }
  }

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
