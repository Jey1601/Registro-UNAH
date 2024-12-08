import { Cell } from "../behavior/support.mjs";

class Section {

    static path = '../../../';
    
  static async renderData(accessess, idTable) {
    let data = [];
    //Verificamos los accesos de la persona logeada
    if (accessess.includes("rllHaveq") || accessess.includes("IeMfti20")) {
      data = await this.viewData();
    } else {
      console.log("vamos por aquí");

      data = await this.viewPendingCheckData();
      console.log(data);
    }

    const tableBody = document.querySelector(`#${idTable} tbody`);
    tableBody.innerHTML = "";

    // Comprobamos que tenemos datos antes de intentar renderizarlos
    if (data && Array.isArray(data) && data.length > 0) {
      var counter = 0;

      data.forEach((item) => {
        counter++;

       

        const cellCertificate = Cell.createCell("td", "");

        const button = document.createElement("button");
        button.classList.add("view-document");
        button.style = "border:none; background:none; width:30px; heigth:30px;";

        button.setAttribute("data-applicant", item.id_applicant);

        // Creamos la imagen y configuramos su fuente
        const viewIcon = document.createElement("img");
        viewIcon.src = this.path + "assets/img/icons/openfile.png";
        viewIcon.style = "width:30px; heigth:30px;";

        // Agregamos la imagen al botón
        button.appendChild(viewIcon);

        cellCertificate.appendChild(button);

        // Agregar cada celda a la fila
        row.appendChild(registerNumber);
        row.appendChild(cellClassId);
        row.appendChild(cellClassName);
        row.appendChild(cellSection);
        row.appendChild(cellId);
        row.appendChild(cellEmail);
        row.appendChild(cellPhone);
        row.appendChild(cellAddress);
        row.appendChild(cellSpots   );
        row.appendChild(cellCenter);
        row.appendChild(cellFirst);
        row.appendChild(cellSecond);
        row.appendChild(cellCertificate);

        // Añadir la fila al cuerpo de la tabla
        tableBody.appendChild(row);
      });

      if (!accessess.includes("rllHaveq")) {
        downloadInscriptionsCsv.style.display = "none";
      }

      accessess.forEach((access) => {
        console.log(access);

        //Agregamos el evento a los botones para poder ver el certificado
        const viewCertificateButtons =
          document.querySelectorAll(".view-document");

        viewCertificateButtons.forEach((button) => {
          button.addEventListener("click", function () {
            Applicant.showDataitem(
              data,
              button.getAttribute("data-applicant"),
              access
            );
          });
        });
      });

      
    } else {
      Alert.display(
        "info",
        "Todo en orden",
        "No se encontraron solicitudes de aplicación activas",
        "../../"
      );
    }
  }

  static addOptions(tableId){

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

      const fourthCellText = cells[2].textContent.trim();
        
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
}

export { Section };
