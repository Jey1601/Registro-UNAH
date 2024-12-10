import { Alert, Cell } from "../behavior/support.mjs";

class Student {
  static path = "../../../../";
  /**
   * Obtiene las secciones de clases matriculadas por un estudiante.
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-12-09
   * @param {string} idStudent - Identificador único del estudiante.
   * @returns {Promise<Array>} Una promesa que resuelve en un arreglo de secciones de clases matriculadas.
   *                            Si ocurre un error, retorna un arreglo vacío.
   * @throws {Error} Si la respuesta del servidor no es exitosa.
   */
  static async getEnrollmentClassSection(idStudent) {
    try {
      const response = await fetch(
        this.path +
          `/api/get/student/getEnrollmentClassSection.php?idStudent=${idStudent}`
      );

      if (!response.ok) {
        throw new Error("Error en la solicitud: " + response.status);
      }

      const data = await response.json();

      if (data.status != "success") {
        Alert.display(data.status, "oh", data.message, "../../../../");
      }
      return data.enrollmentClassSections; // Retorna las clases matriculadas
    } catch (error) {
      return []; // Si hay un error, retornamos un array vacío
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

  static addOptionTableMain(tableId) {
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
      buttonDelete.setAttribute("section", sectionId);
      buttonDelete.innerText = "Eliminar";

      buttonDelete.addEventListener("click", function () {
        //Aquí se llamará a la función que elimina la sección si nos encontramos en proceso de matrícula
      });

      cellOptions.appendChild(buttonDelete);

      row.appendChild(cellOptions); // Agregamos las opciones a la fila
    });
  }


}

export { Student };
