import { Cell, Modal, Alert, Search, Entry } from "../behavior/support.mjs";
import { Inscription } from "./Inscription.mjs";
import { Login } from "./login.mjs";
class Applicant {
  static modalInstance = null;

  static async renderData(accessess) {
    let  applications = [];
    if(accessess.includes('rllHaveq') || accessess.includes('IeMfti20') ){
       applications = await this.viewData();
    }else{
       applications = await this.viewPendingCheckData();
    }
   

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

      
      if(!accessess.includes('rllHaveq')){
        downloadInscriptionsCsv.style.display = 'none';
      }

      accessess.forEach(access =>{
        
        console.log(access);

              //Agregamos el evento a los botones para poder ver el certificado
            const viewCertificateButtons =
                document.querySelectorAll(".view-document");

                viewCertificateButtons.forEach((button) => {
                button.addEventListener("click", function () {
                Applicant.showDataApplication(applications,
                button.getAttribute("data-applicant"),
                access
              );
            });
          });


      })
      

      Search.onInputChange("searchApplication", "viewDataApplicantsBody");

    

    } else {
      Alert.display('info','Todo en orden','No se encontraron solicitudes de aplicación activas','../../');
    }
  }

  //Carga la imagen del certificado del aplicante en la modal y luego la despliega
   static showDataApplication(applications, idApplicant, access) {
   const application = applications.find(applicant => applicant.id_applicant === idApplicant);
    console.log(applications);
    //Apartado de verificación para roles de revisión, no edición.
    const verifyDataCheckList = document.getElementById('verifyDataCheckList');
   
    //Botón de descarga de aplicaciones, solo asociado a role valido
    const downloadInscriptionsCsv = document.getElementById('downloadInscriptionsCsv');

     
    
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
    const id_admission_application_number = document.getElementById('id_admission_application_number');
    const id_check_applicant_applications = document.getElementById('id_check_applicant_applications'); 

    //Inicialmente todo esta deshabilitado
      applicantStudyCenter.disabled = true;
      applicantFirstChoice.disabled = true;
      applicantSecondChoice.disabled = true;
      applicantName.disabled = true;
      applicantLastName.disabled = true;
      applicantIdentification.disabled = true;
      applicantPhoneNumber.disabled = true;
      applicantEmail.disabled = true;
      applicantDirection.disabled = true;
    
    //El apartado de verificación no se muestra

    verifyDataCheckList.style.display = 'none';


    switch(access){

      //Tiene acceso a verificar
      case 'lwx50K7f':
        verifyDataCheckList.style.display = 'block';
     
      
      break;

      //Tiene acceso a editar
      case 'IeMfti20':
      console.log('entreo aquí');  
      applicantName.disabled = false;
      applicantLastName.disabled = false;
      applicantIdentification.disabled = false;
      applicantPhoneNumber.disabled = false;
      applicantEmail.disabled = false;
      applicantDirection.disabled = false;
      break;

      //Tiene acceso a ver y descargar más no editar
      case 'rllHaveq':
        verifyDataCheckList.style.display = 'none';


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
    id_admission_application_number.value = application.id_admission_application_number;
    id_check_applicant_applications.value = application.id_check_applicant_applications;


   
    certificateImage.innerHTML = application.certificate; 
    idImage.innerHTML = application.idImage;

    const modalTitle = document.getElementById("viewCertificateTitle");
    modalTitle.innerText = "Información de Solicitud Número: " +  application.id_admission_application_number;
    // Establecer un tamaño adecuado para la imagen (opcional)
    certificateImage.style.maxWidth = "100%"; // Limitar el ancho de la imagen a 100px
    certificateImage.style.maxHeight = "100%"; // Limitar el alto de la imagen a 100px*/

    Modal.showModal("viewCertificate");
  }


    //Carga la imagen del certificado del aplicante en la modal y luego la despliega
  static  async renderDataToEdit(id_applicant) {

    const result = await this.getResults(id_applicant);
    const data = result.data;
    if(result.status == 'success'){
      const info = document.getElementById('info');
      const note = document.createElement('h5');
      note.textContent = 'Número de solicitud '.concat(data.id_admission_application_number.value);
      note.style.color = '#002c9e'
      info.appendChild(note);

        //Tomamos todos los input del formulario de visualización
        const applicantName = document.getElementById('applicantName');
        const applicantLastName = document.getElementById('applicantLastName');
        const applicantIdentification = document.getElementById('applicantIdentification');
        const applicantPhoneNumber = document.getElementById('applicantPhoneNumber');
        const applicantEmail = document.getElementById('applicantEmail');
        const applicantDirection = document.getElementById('applicantDirection')
        const certificateImage = document.getElementById("applicantCertificate");
        const idImage = document.getElementById("applicantIdDocument");
        const applicantStudyCenter = document.getElementById('applicantStudyCenter');
        const applicantFirstChoice = document.getElementById('applicantFirstChoice');
        const id_admission_application_number = document.getElementById('id_admission_application_number');
        const id_check_applicant_applications = document.getElementById('id_check_applicant_applications'); 


        
      
        // Cambiamos el valor de los input 
        applicantName.value = data.name_applicant.value;
        applicantLastName.value = data.lastname_applicant.value;
        applicantIdentification.value = data.id_applicant.value;
        applicantPhoneNumber.value = data.phone_number_applicant.value;
        applicantEmail.value = data.email_applicant.value;
        applicantDirection.value = data.address_applicant.value;
        applicantStudyCenter.value = data.name_regional_center.value;
        applicantFirstChoice.value = data.firstC.value;
        applicantSecondChoice.value = data.secondC.value;
        id_admission_application_number.value = data.id_admission_application_number.value;
        id_check_applicant_applications.value = data.id_check_applicant_applications.value;

        //Con el pdf y el certificado, si están correctos, no se muestran al usuario

        if(data.secondary_certificate_applicant.readOnly){
        certificateImage.type = 'hidden';
        certificateImage.value = data.secondary_certificate_applicant.value; 
        const note = document.createElement('p');
        note.textContent = 'No hay acciones por realizar';
        note.style.color = '#002c9e'
        certificateImage.parentNode.appendChild(note);
        }

        if(data.image_id_applicant.readOnly){
        idImage.type = 'hidden';
        idImage.value = data.image_id_applicant.value;
        const note = document.createElement('p');
        note.textContent = 'No hay acciones por realizar';
        note.style.color = '#002c9e'
        idImage.parentNode.appendChild(note);
        }

        
        //Habilitamos según sea necesario la edición de información

        applicantName.readOnly = data.name_applicant.readOnly;
        applicantLastName.readOnly = data.lastname_applicant.readOnly;
        applicantIdentification.readOnly = data.id_applicant.readOnly;
        applicantPhoneNumber.readOnly = data.phone_number_applicant.readOnly;
        applicantEmail.readOnly = data.email_applicant.readOnly;
        applicantDirection.readOnly = data.address_applicant.readOnly;
        applicantStudyCenter.readOnly = data.name_regional_center.readOnly;
        applicantFirstChoice.readOnly = data.firstC.readOnly;
        applicantSecondChoice.readOnly = data.secondC.readOnly;


              //Opciones que no se puenden cambiar
              applicantStudyCenter.readOnly = true;
              applicantFirstChoice.readOnly = true;
              applicantSecondChoice.readOnly = true;
      }else{
        Alert.display(result.status, 'Aviso', result.message, '../../');
        window.location.href = './login.html';
      } 
    }
   
  // Método para obtener los datos de las solicitudes de aplicación
  static async viewData() {
    try {
      const response = await fetch("../../../public/api/get/applicant/viewData.php");

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
    const tokenSaved = sessionStorage.getItem('token');
    const payload = Login.getPayloadFromToken(tokenSaved);
    const user = payload.userAdmissionAdmin;

    try {
      const response = await fetch(`../../../public/api/get/applicant/PendingCheckData.php?user=${user}`);

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
    //Ahora obtengo el proceso y diferencio
    console.log(results);
    if(results.view === 'results'){
     
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


    }else  if(results.view === 'data-edition'){

      window.location.href = '../../../views/admissions/data-edition.html'
    }

    
  }

  static async getResults(id_applicant) {
    const formData = new FormData();
    formData.append('id_applicant', id_applicant);

    try {
        const response = await fetch("../../../public/api/post/applicant/getResults.php", {
            method: "POST",
            body: formData,
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const result = await response.json(); // Convertir respuesta a JSON

        
        console.log("Resultado obtenido del servidor:", result);

        if (!result || typeof result !== "object") {
            throw new Error("Respuesta no válida del servidor");
        }

        return result;
    } catch (error) {
        console.error("Error al obtener resultados:", error);
        return null; // Retorna un valor seguro en caso de error
    }
}

static async getData() {
  const dataEditionForm = document.getElementById("dataEditionForm");
  // Crear un nuevo objeto FormData
  const formData = new FormData(dataEditionForm);



  // Obtener los valores de los campos del formulario
  const applicant_name = formData.get("applicantName");
  const applicant_last_name = formData.get("applicantLastName");



  // Obtenemos los input de tipo file
  const certificateImage = document.getElementById("applicantCertificate");
  const idImage = document.getElementById("applicantIdDocument");



  const certificateFile = formData.get("applicantCertificate");
  const idFile = formData.get('applicantIdDocument');

  const allowedTypes = ["image/jpg",  "image/jpeg",  "image/png" ,"application/pdf"];

    //Verificamos si es necesario leer su archivo
    if(certificateImage.type === 'file'){
      if (allowedTypes.includes(certificateFile.type)) {
        const myBlob = new Blob([certificateFile], { type: certificateFile.type });
    
       // const formData = new FormData();
        formData.set("applicantCertificate", myBlob, certificateFile.name);
      }

      const isCertificateValid = await Inscription.validateFile(certificateFile);

              
        if(!isCertificateValid){
          document.getElementById('applicantCertificate').value='';
          Alert.display('error','Algo anda mal',"Revise el tamaño o resolución de su archivo de certificado");
        }
        formData.append('typeCertificate','file');
    }else{
      formData.append('typeCertificate','base64');
    } 
        
        
    if(idImage.type === 'file'){
      if (allowedTypes.includes(idFile.type)) {
        const myBlob = new Blob([idFile], { type: idFile.type });
    
        //const formData = new FormData();
        formData.set("applicantIdDocument", myBlob, idFile.name);
      }

      const isIdValid = await Inscription.validateFile(idFile); 

      if(!isIdValid){
        document.getElementById('applicantIdDocument').value='';
        Alert.display('error','Algo anda mal',"Revise el tamaño o resolución de su archivo de identificación");
      }
      formData.append('typeId','file');
    }else{
      formData.append('typeId','base64');
    }
  
    


  // Dividir el nombre y apellido
  const first_name = applicant_name.split(" ")[0]; // Primer nombre
  const second_name = applicant_name.split(" ")[1] || ""; // Segundo nombre (si existe, si no asignamos null)
  const third_name = applicant_name.split(" ")[2] || ""; // Tercer nombre (si existe, si no asignamos null)
  const first_lastname = applicant_last_name.split(" ")[0]; // Primer apellido
  const second_lastname = applicant_last_name.split(" ")[1] || ""; // Segundo apellido (si existe, si no asignamos null)

  formData.append("applicantFirstName", first_name);
  formData.append("applicantSecondName", second_name); // Si no hay segundo nombre, pasará null
  formData.append("applicantThirdName", third_name); // Si no hay tercer nombre, pasará null
  formData.append("applicantFirstLastName", first_lastname);
  formData.append("applicantSecondLastName", second_lastname); // Si no hay segundo apellido, pasará null

  /*for (let [key, value] of formData.entries()) {
      console.log(`${key}: ${value}`); // Imprime cada clave y valor en la consola 
    } */

      if(idImage.type === 'file' && certificateImage.type === 'file'){
        const isCertificateValid = await Inscription.validateFile(certificateFile);
        const isIdValid = await Inscription.validateFile(idFile); 
        
          if (isCertificateValid && isIdValid) {
            
            Alert.display('warning','Espere','Estamos cargando su información');
           
          
        }
      }
    

      
     
     this.updateData(formData, dataEditionForm);
 

  //Limpiamos el formulario
  //inscriptionForm.reset();
}

static async updateData(formData, form) {
  try {
    // Realizar la solicitud POST usando fetch
    const response = await fetch(
      "../../../public/api/post/applicant/updateApplicant.php",
      {
        method: "POST",
        body: formData,
      }
    )

    const result = await response.json(); 
   
    if (result.id_application == null ){
      Alert.display(result.status,'Aviso', result.message, '../../');

      form.reset();
      
      Array.from(form.elements).forEach(input => {
        input.classList.remove('right-input');
      });

      window.location.href = './login.html'
    }
   
  } catch (error) {
    console.log(error);
    Alert.display('error','Lamentamos decirte esto', 'Hubo un error al cargar la información', '../../');
  }
}

static  async getChecks() {
  // Arreglo para almacenar los valores de los checkboxes activos
  let checkboxesActivos = [];

  // Obtener todos los checkboxes con 'name' de la forma 'invalid_*' o 'secondary_certificate_applicant' (por ejemplo)
  const checkboxes = document.querySelectorAll('.form-check-input:checked');

  // Iteramos sobre los checkboxes seleccionados y obtenemos sus valores
  checkboxes.forEach(function(checkbox) {
      checkboxesActivos.push(checkbox.value);
  });

  // Obtener los valores de los campos adicionales
  const checkJustification =document.getElementById('checkJustification');
  const justification = checkJustification.value;
  //const idAdmissionApplicationNumber = document.getElementById('id_admission_application_number').value;
  const idCheckApplicantApplications = document.getElementById('id_check_applicant_applications').value;

  // Mostrar los valores para depuración (puedes quitarlo en producción)
  console.log('Checkboxes Activos:', checkboxesActivos);
  console.log('Justificación:', justification);
 // console.log('ID de Solicitud:', idAdmissionApplicationNumber);
  console.log('ID de Check Applicant Applications:', idCheckApplicantApplications);
  let verificationStatus = 1;
  if(checkboxesActivos.length>0){
    verificationStatus = 0;
  }

  const revisionStatus = 1;
    // Mostrar los valores para depuración (puedes quitarlo en producción)
    console.log('Checkboxes Activos:', checkboxesActivos);
    console.log('Justificación:', justification);
   // console.log('ID de Solicitud:', idAdmissionApplicationNumber);
    console.log('ID de Check Applicant Applications:', idCheckApplicantApplications);
    console.log('status:', verificationStatus);
    console.log('status:', revisionStatus);

  await this.saveChecks(checkboxesActivos, justification, idCheckApplicantApplications, verificationStatus,revisionStatus);
  

  // Recorrer cada checkbox y desmarcarlo
  checkboxes.forEach(checkbox => {
      checkbox.checked = false;
  });

  checkJustification.value = '';

  Modal.hideModal('viewCertificate');
  window.location.reload() // Mensaje de éxito
  
}


 static async  saveChecks(checkboxesActivos, justification, idCheckApplicantApplications, verificationStatus,revisionStatus) {
  // Crear el objeto de datos que se enviará al backend
  const requestData = {
      idCheckApplicant: idCheckApplicantApplications,
      verificationStatus: verificationStatus, // Los valores de los checkboxes activos
      revisionStatus: revisionStatus, // o el valor adecuado
      descriptionGeneralCheck: justification, // Justificación
      errorData: checkboxesActivos.join(', ') // Unir los valores de los checkboxes activos, si es necesario
  };

  try {
      // Enviar los datos al endpoint utilizando fetch
      const response = await fetch("../../../public/api/post/applicant/checkErrorProcess.php", {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json'
          },
          body: JSON.stringify(requestData) // Convertir el objeto a JSON
      });

      // Obtener la respuesta y procesarla
      const result = await response.json();

      // Mostrar el resultado en consola o tomar las acciones correspondientes
      if (result.success) {
          alert(result.message);
        
      } else {
          Alert.display(result.status,'Aviso',result.message, '../../'); // Mensaje de error
      }
  } catch (error) {
      // Manejo de errores en caso de fallo en la solicitud
      console.error('Error al enviar los datos:', error);
      alert('Hubo un error al enviar los datos.');
  }
}
}
export { Applicant };
