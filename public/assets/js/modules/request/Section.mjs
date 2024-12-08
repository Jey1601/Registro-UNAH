import { Cell } from "../behavior/support.mjs";
import { Table } from "../behavior/support.mjs";
class Section {

    static path = '../../../';
    
  

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
