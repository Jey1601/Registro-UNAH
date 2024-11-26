import { Cell, Modal, Alert, Search, Entry } from "../behavior/support.mjs";
class Applicant {
  static modalInstance = null;

  static async renderData(role) {
    const applications = await this.viewPendingCheckData();
    const tableBody = document.querySelector("#viewDataApplicants tbody");
    tableBody.innerHTML = "";

    // Comprobamos que tenemos datos antes de intentar renderizarlos
    if (
      applications &&
      Array.isArray(applications) &&
      applications.length > 0
    ) {
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
        
        button.setAttribute(
          "data-applicant",
          application.id_applicant
        );

        // Creamos la imagen y configuramos su fuente
        const viewIcon = document.createElement("img");
        viewIcon.src = "../../../assets/img/icons/openfile.png";
        viewIcon.style = "width:30px; heigth:30px;";

        // Agregamos la imagen al botón
        button.appendChild(viewIcon);

        cellCertificate.appendChild(button);

     

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
          Applicant.showDataApplication(applications,
            button.getAttribute("data-applicant"),
            role
          );
        });
      });

      Search.onInputChange("searchApplication", "viewDataApplicantsBody");

       //Botón de descarga de aplicaciones, solo asociado a role valido
       const downloadInscriptionsBtn = document.getElementById('downloadInscriptionsBtn');

      if(role != 3){
        downloadInscriptionsBtn.style.display = 'none';
      }

    } else {
      Alert.display('info','Todo en orden','No se encontraron solicitudes de aplicación activas');
    }
  }

  //Carga la imagen del certificado del aplicante en la modal y luego la despliega
  static showDataApplication(applications, idApplicant, role) {
   const application = applications.find(applicant => applicant.id_applicant === idApplicant);
    
    //Apartado de verificación para roles de revisión, no edición.
    const verifyDataCheckList = document.getElementById('verifyDataCheckList');
   
   
    
    //Tomamos todos los input del formulario de visualización
    const applicantName = document.getElementById('applicantName');
    const applicantLastName = document.getElementById('applicantLastName');
    const applicantIdentification = document.getElementById('applicantIdentification');
    const applicantPhoneNumber = document.getElementById('applicantPhoneNumber');
    const applicantEmail = document.getElementById('applicantEmail');
    const applicantDirection = document.getElementById('applicantDirection')
    const certificateImage = document.getElementById("certificateImage");
    const idImage = document.getElementById("idImage");
    const applicantStudyCenter = document.getElementById('applicantStudyCenter');
    const applicantFirstChoice = document.getElementById('applicantFirstChoice');
    const applicantSecondChoice = document.getElementById('applicantSecondChoice');

    //Si el role no tiene acceso de edición lo quitamos
      applicantStudyCenter.disabled = true;
      applicantFirstChoice.disabled = true;
      applicantSecondChoice.disabled = true;
    
    switch(role){

      case 1:
        verifyDataCheckList.style.display = 'none';
     
   
      break;

      case 2:
       
        applicantName.disabled = true;
        applicantLastName.disabled = true;
        applicantIdentification.disabled = true;
        applicantPhoneNumber.disabled = true;
        applicantEmail.disabled = true;
        applicantDirection.disabled = true;
      break;

      case 3:
        verifyDataCheckList.style.display = 'none';

        applicantName.disabled = true;
        applicantLastName.disabled = true;
        applicantIdentification.disabled = true;
        applicantPhoneNumber.disabled = true;
        applicantEmail.disabled = true;
        applicantDirection.disabled = true;

      break;
    }

 
   
    
    // Cambiamos el valor de los input 
    applicantName.value = application.name;
    applicantLastName.value = application.lastname;
    applicantIdentification.value = application.id_applicant;
    applicantPhoneNumber.value = application.phone_number_applicant;
    applicantEmail.value = application.email_applicant;
    applicantDirection.value = application.address_applicant;
    applicantStudyCenter.value = application.name_regional_center;
    applicantFirstChoice.value = application.firstC;
    applicantSecondChoice.value = application.secondC;


   
    certificateImage.innerHTML = application.certificate; 
    idImage.innerHTML = application.idImage;

    const modalTitle = document.getElementById("viewCertificateTitle");
    modalTitle.innerText = "Información de Solicitud Número: " +  application.id_admission_application_number;
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

      return data;
    } catch (error) {
      return []; // Si hay un error, retornamos un array vacio
    }
  }

  static async viewPendingCheckData() {
    try {
      const response = await fetch("../../../api/get/applicant/PendingCheckData.php");

      if (!response.ok) {
        throw new Error("Error en la solicitud: " + response.status);
      }

      const data = await response.json();

      return data;
    } catch (error) {
      return []; // Si hay un error, retornamos un array vacio
    }
  }

  static async renderResults(id_applicant) {
    const results = await this.getResults(id_applicant);

    if (!(Object.keys(results.resolutions).length === 0)) {
      const information = document.getElementById("resultsInformation");
      information.innerHTML = ""; 

      const tableBody = document.querySelector("#resultsTable tbody");
      tableBody.innerHTML = "";
      

      const entry1 = Entry.createEntry("Número de solicitud : ", results.resultsTest[0].id_admission_application_number);
     
      information.appendChild(entry1);
      
      // Renderizar el nombre
      const entry2 = Entry.createEntry("Nombre : ", results.resultsTest[0].name);
      information.appendChild(entry2);
      
      // Renderizar el número de identidad
      const entry3 = Entry.createEntry("Número de identidad : ", results.resultsTest[0].id_applicant);
      information.appendChild(entry3);

      // Renderizar los exámenes realizados con sus calificaciones
      results.resultsTest.forEach((result) => {
        const entryExam = Entry.createEntry(result.name_type_admission_tests.concat(" : ") , result.rating_applicant);
        information.appendChild(entryExam);
      });
      let counter = 0;
      //Renderizamos la tabla de resultados
      let career = 0;
      results.resolutions.forEach((resolution) => {
        counter++;

        const row = document.createElement("tr");

        // Celdas de datos
        const registerNumber = Cell.createCell("th", counter);
        const CellCareer = Cell.createCell("td", resolution.name_undergraduate);
        const cellCenter = Cell.createCell(
          "td",
          resolution.name_regional_center
        );
        const cellResolution = Cell.createCell(
          "td",
          resolution.resolution_intended === 1 ? "Aceptado" : "Rechazado"
        );

        const cellSelection = Cell.createCell("td", "");
        cellSelection.id="resolution".concat(counter);
        cellSelection.setAttribute("data-resolution", resolution.id_resolution_intended_undergraduate_applicant); 
        if( resolution.resolution_intended === 1 ){
            //Se crea el radiocheck

            // Crear el contenedor principal
            const div = document.createElement("div");
            div.className = "form-check";

            // Crear el input radio
            const input = document.createElement("input");
            input.className = "form-check-input";
            input.type = "radio";
            input.name = "option";
            input.value = resolution.id_notification_application_resolution;
            input.id = "firstOption";
           
               
         


            // Agregar el input al div
            div.appendChild(input);

            

            cellSelection.appendChild(div);
        }
      

        // Agregar cada celda a la fila
        row.appendChild(registerNumber);
        row.appendChild(CellCareer);
        row.appendChild(cellCenter);
        row.appendChild(cellResolution);
        row.appendChild(cellSelection);
       

        // Añadir la fila al cuerpo de la tabla
        tableBody.appendChild(row);

        career ++;
      });
    } else {
      const submitBtn = document.getElementById('submitBtn');
      submitBtn.remove();
      Alert.display("No se encontraron resultados", "warning");
    }
  }

  static async getResults(id_applicant ) {
    //const inscriptionForm = document.getElementById("loginApplicant");
    // Crear un nuevo objeto FormData
    const formData = new FormData();
    formData.append('id_applicant', id_applicant);

    try {
      // Realizar la solicitud POST usando fetch
      const response = await fetch(
        "../../../api/post/applicant/getResults.php",
        {
          method: "POST",
          body: formData,
        }
      );

      const result = await response.json(); // Esperamos que la respuesta sea convertida a JSON
      console.log(result);

      return result;
    } catch (error) {
      console.error("Error:", error); // Manejamos el error si ocurre
    }
  }
}

export { Applicant };
