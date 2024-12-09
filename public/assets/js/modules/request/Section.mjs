import { Cell } from "../behavior/support.mjs";
import { Table } from "../behavior/support.mjs";
import { Alert } from "../behavior/support.mjs";
class Section {
  static path = "../../../";

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

      const sectionId = cells[2].textContent.trim();

      //Celda que contendrá las opciones
      const cellOptions = Cell.createCell("td", "");

      //botón que despliga la modal para agregar video
      const buttonVideo = document.createElement("button");
      buttonVideo.classList.add("btn");
      buttonVideo.classList.add("btn-video");
      buttonVideo.setAttribute("section", fourthCellText);

      // Creamos la imagen y configuramos su fuente
      const videoIcon = document.createElement("img");
      videoIcon.src = this.path + "assets/img/icons/add-video-icon.png";

      const buttonDownload = document.createElement("button");
      buttonDownload.classList.add("btn");
      buttonDownload.classList.add("btn-download");
      buttonDownload.setAttribute("section", fourthCellText);

      // Creamos la imagen y configuramos su fuente
      const downloadIcon = document.createElement("img");
      downloadIcon.src = this.path + "assets/img/icons/download-grey-icon.png";

      const buttonGrade = document.createElement("button");
      buttonGrade.classList.add("btn");
      buttonGrade.classList.add("btn-grade");
      buttonGrade.setAttribute("section", fourthCellText);

      // Creamos la imagen y configuramos su fuente
      const gradeIcon = document.createElement("img");
      gradeIcon.src = this.path + "assets/img/icons/grade-icon.png";

      // Agregamos la imagen al botón
      buttonVideo.appendChild(videoIcon);
      buttonDownload.appendChild(downloadIcon);
      buttonGrade.appendChild(gradeIcon);

      cellOptions.appendChild(buttonVideo);
      cellOptions.appendChild(buttonDownload);
      cellOptions.appendChild(buttonGrade);

      row.appendChild(cellOptions); // Agregamos las opciones a la fila
    });
  }

  static addOptionsAcademicPlanning(tableId) {
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
      const sectionId = cells[0].textContent.trim();

      const spotsCell = cells[5];
      const spots = cells[5].textContent.trim();
      const divButtons = document.createElement("div");
      divButtons.style.display = "flex";
      divButtons.style.justifyContent = "center";
      //botón que agrega un cupo a la sección

      const buttonPlus = document.createElement("button");
      buttonPlus.classList.add("btn");

      buttonPlus.classList.add("btn-plus");
      buttonPlus.setAttribute("section", sectionId);
      buttonPlus.setAttribute("spots", spots);

      // Creamos la imagen y configuramos su fuente
      const plusIcon = document.createElement("img");
      plusIcon.src = this.path + "assets/img/icons/plus-icon.png";

      //botón que resta un cupo a la sección

      const buttonMinus = document.createElement("button");
      buttonMinus.classList.add("btn");
      buttonMinus.classList.add("btn-minus");
      buttonMinus.setAttribute("section", sectionId);
      buttonMinus.setAttribute("spots", spots);
      // Creamos la imagen y configuramos su fuente
      const minusIcon = document.createElement("img");
      minusIcon.src = this.path + "assets/img/icons/minus-icon.png";

      // Agregamos la imagen al botón
      buttonMinus.appendChild(minusIcon);
      buttonPlus.appendChild(plusIcon);

      buttonPlus.addEventListener("click", () => {
        this.updateSpotsAvailableClassSectionAcademicPlanning(
          buttonPlus.getAttribute("section"),
          parseInt(buttonPlus.getAttribute("spots"), 10) + 1,
          spotsCell,
          buttonPlus,
          buttonMinus
        );
      });

      buttonMinus.addEventListener("click", () => {
        this.updateSpotsAvailableClassSectionAcademicPlanning(
          buttonMinus.getAttribute("section"),
          parseInt(buttonMinus.getAttribute("spots"), 10) - 1,
          spotsCell,
          buttonPlus,
          buttonMinus
        );
      });

      divButtons.appendChild(buttonMinus);
      divButtons.appendChild(buttonPlus);

      //Celda que contendrá las opciones
      const cellOptions = Cell.createCell("td", "");
      const cellSpots = Cell.createCell("td", "");
      //botón que despliga la modal para agregar video
      const buttonDelete = document.createElement("button");
      buttonDelete.classList.add("btn");
      buttonDelete.classList.add("btn-danger");
      buttonDelete.setAttribute("section", sectionId);
      buttonDelete.innerText = "Eliminar";

      cellSpots.appendChild(divButtons);

      cellOptions.appendChild(buttonDelete);

      row.appendChild(cellSpots); // // Agregamos las opciones a la fila
      row.appendChild(cellOptions);
     
    });
  }

  static async updateSpotsAvailableClassSectionAcademicPlanning(
    id_class_section,
    new_spots_number,
    spotsCell,
    buttonPlus,
    buttonMinus
  ) {

    if(new_spots_number <= 0){
      Alert.display('warning','Oh no', 'No pueden haber cupos vacios o negativos', '../../../../');
      return;
    }
    const data = {
      id_class_section: parseInt(id_class_section, 10),
      new_spots_number: parseInt(new_spots_number, 10),
    };

    try {
      const response = await fetch(
        this.path +
          "api/post/academicPlanning/PostUpdateSpotsAvailableClassSectionAcademicPlanning.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );

      const responseData = await response.json();
      if (responseData.status == "success") {
        spotsCell.textContent = new_spots_number;
        buttonPlus.setAttribute("spots", new_spots_number);
        buttonMinus.setAttribute("spots", new_spots_number);
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
    } catch (error) {
      console.error("Error:", error);
    }
  }
}

export { Section };
