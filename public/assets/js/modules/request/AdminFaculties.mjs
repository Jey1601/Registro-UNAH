import { File, Alert } from "../behavior/support.mjs";

class Professor {
  static path = "../../../";

  /**
   * Obtiene y valida la información del formulario de creación de un profesor,
   * ajustando los datos antes de enviarlos.
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-12-04
   * @returns {Promise<void>} Retorna una promesa, pero no devuelve ningún valor.
   * @throws {Error} Si la validación del archivo de imagen falla o si ocurre un error en la solicitud.
   */

  static async getData() {
    const professorCreationForm = document.getElementById(
      "professorCreationForm"
    );
    // Crear un nuevo objeto FormData
    const formData = new FormData(professorCreationForm);

    const mayusName = formData.get("professorName");
    const mayusLastName = formData.get("professorLastName");

    // Convertir a mayúsculas y luego establecer el valor en formData
    formData.set("professorName", mayusName.toUpperCase());
    formData.set("professorLastName", mayusLastName.toUpperCase());

    // Obtener los valores de los campos del formulario
    const professor_name = formData.get("professorName");
    const professor_last_name = formData.get("professorLastName");

    const professorPictureFile = formData.get("professorPicture");

    const allowedTypes = ["image/jpg", "image/jpeg", "image/png"];

    if (allowedTypes.includes(professorPictureFile.type)) {
      const myBlob = new Blob([professorPictureFile], {
        type: professorPictureFile.type,
      });

      // const formData = new FormData();
      formData.set("professorPictureFile", myBlob, professorPictureFile.name);
    }

    // Dividir el nombre y apellido
    const first_name = professor_name.split(" ")[0]; // Primer nombre
    const second_name = professor_name.split(" ")[1] || ""; // Segundo nombre (si existe, si no asignamos null)
    const third_name = professor_name.split(" ")[2] || ""; // Tercer nombre (si existe, si no asignamos null)
    const first_lastname = professor_last_name.split(" ")[0]; // Primer apellido
    const second_lastname = professor_last_name.split(" ")[1] || ""; // Segundo apellido (si existe, si no asignamos null)

    formData.append("professorFirstName", first_name);
    formData.append("professorSecondName", second_name); // Si no hay segundo nombre, pasará null
    formData.append("professorThirdName", third_name); // Si no hay tercer nombre, pasará null
    formData.append("professorFirstLastName", first_lastname);
    formData.append("professorSecondLastName", second_lastname); // Si no hay segundo apellido, pasará null

    const isPictureValid = await File.validateFile(professorPictureFile);

    if (isPictureValid) {
      Alert.display(
        "warning",
        "Espere",
        "Estamos cargando su información",
        this.path
      );
      this.insertData(formData, professorCreationForm);
    
    }
  }

  /**
   * Inserta los datos del formulario a través de una solicitud POST y maneja la respuesta.
   * Si la inserción es exitosa, resetea el formulario y muestra un mensaje de éxito.
   * Si ocurre algún error, muestra un mensaje de error.
   *
   * @param {FormData} formData - Los datos del formulario que serán enviados en la solicitud.
   * @param {HTMLFormElement} form - El formulario del cual se recopilaron los datos.
   * @returns {Promise<void>} Retorna una promesa, pero no devuelve ningún valor.
   * @throws {Error} Si ocurre un error al hacer la solicitud o procesar la respuesta.
   */

  static async insertData(formData, form) {
    try {
      // Realizar la solicitud POST usando fetch
      const response = await fetch(
        this.path + "/api/post/facultyAdmin/createProfessor.php",
        {
          method: "POST",
          body: formData,
        }
      );

      const result = await response.json();
      console.log(result);
      //Revisar que devuelve el result;
      if (result.id_application == null) {
        Alert.display('success', "Aviso", result.message, this.path);
      } else {
        form.reset();

        Array.from(form.elements).forEach((input) => {
          input.classList.remove("right-input");
        });

        Alert.display("success", "Felicidades", result.message, this.path);
      }
    } catch (error) {
      console.log(error);
      Alert.display(
        "error",
        "Lamentamos decirte esto",
        "Hubo un error al cargar la información",
        this.path
      );
    }
  }

  static async getProfessorsByFaculty(idFaculty) {
    try {
      const response = await fetch(
        this.path +
          `/api/get/facultyAdmin/getProfessorsByFaculty.php?idFaculty=${idFaculty}`
      );

      if (!response.ok) {
        throw new Error("Error en la solicitud: " + response.status);
      }

      const data = await response.json();
      
      return data;
    } catch (error) {
      return [];
    }
  }

  static addOptions(tableId) {
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

      const idProfessor = cells[0].textContent.trim();
      const statusCell = cells[6];
      const status = cells[6].textContent.trim();

      //botón que desactiva a los usuarios
      const buttonUpdate = document.createElement("button");

      buttonUpdate.classList.add("btn");
      if (status == 1) {
        buttonUpdate.classList.add("btn-danger");
        buttonUpdate.innerText = "Desactivar";
      } else {
        buttonUpdate.classList.add("btn-warning");
        buttonUpdate.innerText = "Activar";
      }

      buttonUpdate.addEventListener("click", () => {
        console.log(idProfessor);
        this.changeStateProfessor(idProfessor, buttonUpdate);
      });

      statusCell.textContent = "";
      statusCell.appendChild(buttonUpdate);
    });
  }

  static async changeStateProfessor(idProfessor, buttonUpdate) {
    const data = {
      idProfessor: parseInt(idProfessor, 10),
    };

    try {
      const response = await fetch(
        this.path + "api/put/facultyAdmin/changeStateProfessor.php",
        {
          method: "PUT",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );

      const responseData = await response.json();

      if (responseData.success == true) {
        if (buttonUpdate.classList.contains("btn-danger")) {
          buttonUpdate.classList.remove("btn-danger");
          buttonUpdate.classList.add("btn-warning");
          buttonUpdate.innerText = "Activar";
        } else {
          buttonUpdate.classList.add("btn-danger");
          buttonUpdate.classList.remove("btn-warning");
          buttonUpdate.innerText = "Desactivar";
        }
        Alert.display(
          "success",
          "Enhorabuena",
          responseData.message,
          this.path
        );
      } else {
        Alert.display("warning", "oh", responseData.message, this.path);
      }
    } catch (error) {
      console.error("Error:", error);
    }
  }
}

export { Professor };
