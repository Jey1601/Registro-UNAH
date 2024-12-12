import { Alert, Modal } from "../behavior/support.mjs";
import { Login } from "./login.mjs";

class AcademicPlanning {
  static path = "../../../../";

  /**
   * Verifica el estado del proceso de planificación académica y redirige o muestra un modal de advertencia según corresponda.
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-12-04
   * @returns {Promise<void>} Una promesa que no devuelve ningún valor.
   * @throws {Error} Si la solicitud al servidor falla.
   */

  static async verityAcademicPlanning() {
    try {
      const response = await fetch(
        this.path + "api/get/academicPlanning/verityAcademicPlanning.php"
      );

      if (!response.ok) {
        throw new Error("Error en la solicitud: " + response.status);
      }

      const data = await response.json();

      if (data.status == "success") {
        window.location.href =
          this.path + "views/administration/departments/academic-planning.html";
      } else {
        const body = document.querySelector("#warningModal .modal-body");
        const footer = document.querySelector("#warningModal .modal-footer");
        const warningModalLabel = document.getElementById("warningModalLabel");
        warningModalLabel.innerText = "";
        warningModalLabel.innerText = "Proceso de planificación académica";
        // Limpiar contenido existente
        body.innerHTML = "";
        footer.innerHTML = "";

        // Crear el contenedor centralizado
        const centeredContainer = document.createElement("div");
        centeredContainer.className =
          "d-flex flex-column justify-content-center align-items-center text-center";

        // Crear y agregar imagen con animación
        const imgContainer = document.createElement("div");
        imgContainer.className = "mb-4";

        const img = document.createElement("img");
        img.src = "../../../assets/img/icons/clock-icon.png";
        img.alt = "";
        img.className = "animated-icon";
        imgContainer.appendChild(img);

        // Crear y agregar título
        const title = document.createElement("p");
        title.className = "fs-4";
        title.textContent =
          "El proceso de planificación academica aún no está activo.";

        // Crear y agregar párrafo de información adicional
        const infoParagraph = document.createElement("p");
        infoParagraph.className = "mt-4";
        infoParagraph.innerHTML = `
                    Revisa las fechas del proceso en.
                    <a href="https://www.unah.edu.hn/calendarios" class="text-decoration-none text-primary fw-bold">
                        Calendarios
                    </a> 
                `;

        // Agregar todos los elementos al contenedor centralizado
        centeredContainer.appendChild(imgContainer);
        centeredContainer.appendChild(title);
        centeredContainer.appendChild(infoParagraph);

        // Agregar el contenedor al cuerpo del modal
        body.appendChild(centeredContainer);

        // Mostrar la modal
        Modal.showModal("warningModal");
      }
    } catch (error) {
      console.log(error);
      Alert.display("error", "Algo ha salido mal", "Lo sentimos", this.path);
    }
  }

  /**
   * Obtiene los horarios académicos asociados a la planificación académica desde el servidor.
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-12-04
   * @returns {Promise<Array>} Una promesa que resuelve con los datos de los horarios académicos o un array vacío en caso de error.
   * @throws {Error} Si la solicitud al servidor falla.
   */
  static async getDataAcademicSchedulesAcademicPlanning() {
    try {
      const response = await fetch(
        this.path +
          "api/get/academicPlanning/getDataAcademicSchedulesAcademicPlanning.php"
      );

      if (!response.ok) {
        throw new Error("Error en la solicitud: " + response.status);
      }

      const data = await response.json();

      return data.data;
      // Retorna los centros regionales
    } catch (error) {
      return []; // Si hay un error, retornamos un array vacío
    }
  }

  /**
   * Envía una solicitud POST para obtener los centros regionales asociados a la planificación académica de un profesor.
   * Se asocia el profesor con su departamento, y los centros regionales donde está presente ese departamento.
   * @author Jeyson Espinal (20201001015)
   * @created 2024-12-04
   * @param {int} idProfessor - Identificador del profesor.
   * @returns {Promise<Object|null>} Una promesa que resuelve con los datos de los centros regionales o `null` en caso de error.
   * @throws {Error} Si ocurre un problema durante la solicitud.
   */

  static async regionalCentersAcademicPlanning(idProfessor) {
    const data = {
      username_user_professor: idProfessor, // Sustituye con el valor que quieras enviar
    };

    try {
      const response = await fetch(
        this.path +
          "api/post/academicPlanning/regionalCentersAcademicPlanning.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );

      const responseData = await response.json();

      return responseData.data;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }
  }

  /**
   * Envía una solicitud POST para obtener la planificación académica de los estudiantes de pregrado asociados a un profesor en un centro regional.
   * Se envía el nombre de usuario del profesor y el ID del centro regional para obtener los datos relevantes.
   * @author Jeyson Espinal (20201001015)
   * @created 2024-12-04
   * @param {int} username_user_professor - Nombre de usuario del profesor.
   * @param {int} id_regionalcenter - Identificador del centro regional donde se encuentra el profesor.
   * @returns {Promise<Object|null>} Una promesa que resuelve con los datos de la planificación académica de los estudiantes de pregrado o `null` en caso de error.
   * @throws {Error} Si ocurre un problema durante la solicitud.
   */

  static async UndergraduatesAcademicPlanning(
    username_user_professor,
    id_regionalcenter
  ) {
    const data = {
      username_user_professor: username_user_professor,
      id_regionalcenter: id_regionalcenter,
    };

    try {
      const response = await fetch(
        this.path +
          "api/post/academicPlanning/UndergraduatesAcademicPlanning.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );

      const responseData = await response.json();

      return responseData.data;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }
  }

  /**
   * Envía una solicitud POST para obtener los programas de pregrado asociados a un profesor y un centro regional específico en el contexto de la planificación académica.
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-12-04
   * @param {int} username_user_professor - Identificador del profesor.
   * @param {int} id_regionalcenter - Identificador del centro regional.
   * @returns {Promise<Object|null>} Una promesa que resuelve con los datos de los programas de pregrado o `null` en caso de error.
   * @throws {Error} Si ocurre un problema durante la solicitud.
   */
  static async getDataProfessorsAcademicPlanning(
    regionalCenter,
    username_user_professor,
    days,
    startTime,
    endTime
  ) {
    const data = {
      username_user_professor: username_user_professor,
      id_regionalcenter: regionalCenter,
      days: days,
      startTime: startTime,
      endTime: endTime,
    };

    try {
      const response = await fetch(
        this.path +
          "api/post/academicPlanning/PostDataProfessorsAcademicPlanning.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );

      const responseData = await response.json();

      return responseData.data;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }
  }

  /**
   * Envía una solicitud POST para obtener información sobre la infraestructura asociada a un profesor y un centro regional en el contexto de la planificación académica.
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-12-04
   * @param {int} username_user_professor - Identificador del profesor.
   * @param {int} id_regionalcenter - Identificador del centro regional.
   * @returns {Promise<Object|null>} Una promesa que resuelve con los datos de infraestructura o `null` en caso de error.
   * @throws {Error} Si ocurre un problema durante la solicitud.
   */

  static async getDataInfrastructureAcademicPlanning(
    username_user_professor,
    id_regionalcenter
  ) {
    const data = {
      username_user_professor: username_user_professor,
      id_regionalcenter: id_regionalcenter,
    };

    try {
      const response = await fetch(
        this.path +
          "api/post/academicPlanning/PostDataInfrastructureAcademicPlanning.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data), // Convierte el objeto JavaScript a JSON
        }
      );

      const responseData = await response.json(); // Convierte la respuesta en formato JSON

      return responseData.data;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }
  }

  /**
   * Envía una solicitud POST para actualizar las clases en la planificación académica de un pregrado específico en un periodo académico determinado.
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-12-07
   * @param {int} idUndergraduate - Identificador del pregrado.
   * @param {int} academicPeriodicity - Periodicidad académica asociada al pregrado (ej. semestre o trimestre).
   * @returns {Promise<Object|null>} Una promesa que resuelve con los datos de la respuesta o `null` en caso de error.
   * @throws {Error} Si ocurre un problema durante la solicitud.
   */
  static async postDataClassesAcademicPlanning(
    idUndergraduate,
    academicPeriodicity
  ) {
    const data = {
      idUndergraduate: idUndergraduate,
      academicPeriodicity: academicPeriodicity,
    };

    try {
      const response = await fetch(
        this.path +
          "api/post/academicPlanning/PostDataClassesAcademicPlanning.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data), // Convierte el objeto JavaScript a JSON
        }
      );

      const responseData = await response.json(); // Convierte la respuesta en formato JSON

      if (responseData.status == "success") {
        Alert.display(
          responseData.status,
          "Enhorabuena",
          "Clases actualizadas",
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

      return responseData.data;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }
  }

  /**
   * Envía una solicitud POST para obtener las secciones de clases relacionadas con la planificación académica de un departamento y centro regional específicos.
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-12-07
   * @param {int} department_id - Identificador del departamento académico.
   * @param {int} regional_center_id - Identificador del centro regional.
   * @returns {Promise<Object|null>} Una promesa que resuelve con los datos de las secciones de clases o `null` en caso de error.
   * @throws {Error} Si ocurre un problema durante la solicitud.
   */
  static async getClassSectionByDepartmentHeadAcademicPlanning(
    department_id,
    regional_center_id
  ) {
    const data = {
      department_id: department_id,
      regional_center_id: regional_center_id,
    };

    try {
      const response = await fetch(
        this.path +
          "api/post/academicPlanning/PostClassSectionByDepartmentHeadAcademicPlanning.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data), // Convierte el objeto JavaScript a JSON
        }
      );

      const responseData = await response.json(); // Convierte la respuesta en formato JSON

      if (responseData.status == "success") {
        Alert.display(
          responseData.status,
          "Enhorabuena",
          "Secciones actualizadas",
          this.path
        );
      } else {
        Alert.display(responseData.status, "oh", "Algo anda mal", this.path);
      }

      return responseData.data;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }
  }

  /**
   * Crea una nueva sección de clase en la planificación académica enviando los datos del formulario al servidor.
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-12-07
   * @param {FormData} formData - Objeto FormData que contiene los datos del formulario de la nueva sección de clase.
   * @returns {Promise<number|null>} Una promesa que resuelve con el ID de la nueva sección de clase o `null` si ocurre un error.
   * @throws {Error} Si ocurre un problema durante la solicitud.
   */
  static async createClassSectionAcademicPlanning(formData) {
    const data = {
      id_class: parseInt(formData.get("id_class"), 10),
      id_dates_academic_periodicity_year: parseInt(
        formData.get("id_dates_academic_periodicity_year"),
        10
      ),
      id_classroom_class_section: parseInt(
        formData.get("id_classroom_class_section"),
        10
      ),
      id_academic_schedules: parseInt(
        formData.get("id_academic_schedules"),
        10
      ),
      id_professor_class_section: parseInt(
        formData.get("id_professor_class_section"),
        10
      ),
      numberof_spots_available_class_section: parseInt(
        formData.get("numberof_spots_available_class_section"),
        10
      ),
      status_class_section: 1,
    };

    try {
      const response = await fetch(
        this.path +
          "api/post/academicPlanning/PostcreateClassSectionAcademicPlanning.php",
        {
          method: "POST",
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

      return responseData.idClassSection;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }
  }

  /**
   * Asocia los días a una sección de clase en la planificación académica.
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-12-07
   * @param {int} newClassSectionId - El ID de la nueva sección de clase.
   * @param {Array} days - Un array con los días en los que se llevará a cabo la sección de clase.
   * @returns {Promise<Object|null>} Una promesa que resuelve con los datos de la respuesta del servidor o `null` si ocurre un error.
   * @throws {Error} Si ocurre un problema durante la solicitud.
   */

  static async associateSlassSectionsDaysAcademicPlanning(
    newClassSectionId,
    days
  ) {
    const data = {
      newClassSectionId: parseInt(newClassSectionId, 10),
      days: days,
    };
    console.log(data);
    try {
      const response = await fetch(
        this.path +
          "api/post/academicPlanning/PostClassSectionsDaysAcademicPlanning.php",
        {
          method: "POST",
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

      return responseData.data;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }
  }
}

export { AcademicPlanning };
