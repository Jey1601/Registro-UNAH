import { Cell, Modal, Alert,Search } from "../behavior/support.mjs";
class Applicant {
  static modalInstance = null;

  static async renderData() {
   

    const applications = await this.viewData();

    const tableBody = document.querySelector("#viewDataApplicants tbody");
    tableBody.innerHTML = "";
   

    // Comprobamos que tenemos datos antes de intentar renderizarlos
    if (applications && Array.isArray(applications ) && applications.length > 0) {
      var counter = 0;

      applications.forEach((application) => {
        counter++;

        const row = document.createElement("tr");

        // Celdas de datos
        const registerNumber = Cell.createCell("th", counter);
        const CellSolicitud = Cell.createCell(
          "td",
          application.id_admission_application_number
        );
        const cellName = Cell.createCell("td", application.name);
        const cellLastName = Cell.createCell("td", application.lastname);
        const cellId = Cell.createCell("td", application.id_applicant);
        const cellEmail = Cell.createCell("td", application.email_applicant);
        const cellPhone = Cell.createCell(
          "td",
          application.phone_number_applicant
        );
        const cellAddress = Cell.createCell(
          "td",
          application.address_applicant
        );
        const cellProcess = Cell.createCell(
          "td",
          application.name_admission_process
        );
        const cellCenter = Cell.createCell(
          "td",
          application.name_regional_center
        );
        const cellFirst = Cell.createCell("td", application.firstC);
        const cellSecond = Cell.createCell("td", application.secondC);
        const cellCertificate = Cell.createCell("td", "");

        const button = document.createElement("button");
        button.classList.add("view-document");
        button.style = "border:none; background:none; width:30px; heigth:30px;";
    
        button.setAttribute('data-certificate', application.certificate);
        button.setAttribute('data-application', application.id_admission_application_number);

        // Creamos la imagen y configuramos su fuente
        const viewIcon = document.createElement("img");
        viewIcon.src = "../../../assets/img/icons/openfile.png";
        viewIcon.style = "width:30px; heigth:30px;";

        // Agregamos la imagen al botón
        button.appendChild(viewIcon);

        cellCertificate.appendChild(button);

        // Añadir la fila a la tabla
        tableBody.appendChild(row);

        // Agregar cada celda a la fila
        row.appendChild(registerNumber);
        row.appendChild(CellSolicitud);
        row.appendChild(cellName);
        row.appendChild(cellLastName);
        row.appendChild(cellId);
        row.appendChild(cellEmail);
        row.appendChild(cellPhone);
        row.appendChild(cellAddress);
        row.appendChild(cellProcess);
        row.appendChild(cellCenter);
        row.appendChild(cellFirst);
        row.appendChild(cellSecond);
        row.appendChild(cellCertificate);

        // Añadir la fila al cuerpo de la tabla
        tableBody.appendChild(row);
      });

      //Agregamos el evento a los botones para poder ver el certificado
      const viewCertificateButtons =
        document.querySelectorAll(".view-document");
      viewCertificateButtons.forEach((button) => {
        button.addEventListener("click", function () {
        
          Applicant.showCertificate(button.getAttribute('data-certificate'),button.getAttribute('data-application'));
        });
      });

      Search.onInputChange('searchApplication','viewDataApplicantsBody');
      
    } else{
      Alert.display("No se encontraron solicitudes de aplicación","warning")  
      
    }
  }

  //Carga la imagen del certificado del aplicante en la modal y luego la despliega
    static showCertificate(certificate,IdApplication) {
  
    const certificateImage = document.getElementById("certificateImage");
    certificateImage.src = certificate; // La cadena base64 con el tipo MIME
    
    const modalTitle = document.getElementById('viewCertificateTitle');
    modalTitle.innerText= "Certificado de Solicitud Número: " +IdApplication;
    // Establecer un tamaño adecuado para la imagen (opcional)
    certificateImage.style.maxWidth = "100%"; // Limitar el ancho de la imagen a 100px
    certificateImage.style.maxHeight = "100%"; // Limitar el alto de la imagen a 100px*/

    Modal.showModal("viewCertificate");
  }

  // Método para obtener los datos de las solicitudes de aplicación
  static async viewData() {
    try {
      const response = await fetch("../../../api/get/applicant/viewData.php");

      if (!response.ok) {
        throw new Error("Error en la solicitud: " + response.status);
      }

      const data = await response.json();
      //console.log("Datos recibidos:", data);
      return data;
    } catch (error) {
      //console.error("Hubo un error:", error);
      return []; // Si hay un error, retornamos un array vacio
    }
  }

  
  
}

export { Applicant };
