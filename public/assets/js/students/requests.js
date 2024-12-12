import { Modal, Form, File, Table } from "../modules/behavior/support.mjs";
import { modalTemplates } from "../modules/templates/students/requests/modalTemplates.mjs";
import { Student } from "../modules/request/Student.mjs";
import { Sidebar } from "../modules/behavior/support.mjs";
import { RequestExceptionalCancellation } from "../modules/request/RequestExceptionalCancellation.mjs";
import { Login } from "../modules/request/login.mjs";
import { EnrollmentProcess } from "../modules/request/EnrollmentProcess.mjs";
/* ========== Constantes  ============*/
const toggleSidebarButton = document.getElementById("toggleSidebar");
const closeSidebarButton = document.getElementById("closeSidebar");
const termsBtn = document.getElementById('termsBtn');
const excepcionalCancelationForm = document.getElementById('excepcionalCancelationForm');
const inputsExcepcionalCancelationForm = Array.from(excepcionalCancelationForm.elements);
const submitButton = document.getElementById('sendButton');
let idStudent  = '';
/* ========== Side Bar ============*/
toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);

Sidebar.buildSidebar('../../../')


/* ========== Cargando las clases matrículadas en caso de cancelación excepcional ============*/
window.addEventListener('load', async function(){
  
  const token = sessionStorage.getItem('token'); // Obtén el token del sessionStorage

  try {
    
    const payload = Login.getPayloadFromToken(token);
    idStudent = payload.username;
   
  } catch (error) {
    // Si ocurre un error, simplemente no se ejecuta el resto del código.
    console.log(error);
    this.window.location.href ='../../../index.html'
  }

  const sections = await Student.getEnrollmentClassSection(idStudent);
  
  Table.renderDynamicTable(sections,'viewSections');
  RequestExceptionalCancellation.addOptionTableRequestCancellation('viewSections');
});


/* ========== Mostrando la modal con los terminos de la solicitud ============*/

termsBtn.addEventListener('click', function(){
    console.log('aqui');
    const modalBody = document.querySelector('.modal-body');
    modalBody.innerHTML = modalTemplates['exceptionalCancellationTerms']; // Inserta la plantilla dinámica
    Modal.showModal('warningModal');
     
})




/* ========== Validando el formato de los input ============*/

document.getElementById('justificationFile').addEventListener('change', function(event) {
    const fileInput = event.target;  // Referencia al input
    const file = fileInput.files[0]; // Obtener el archivo seleccionado
    
    File.validateFile(file, fileInput,'../../../');
  
  });

  document.getElementById('formatFile').addEventListener('change', function(event) {
    const fileInput = event.target;  // Referencia al input
    const file = fileInput.files[0]; // Obtener el archivo seleccionado
    
    File.validateFile(file, fileInput,'../../../');
  
  });




inputsExcepcionalCancelationForm.forEach((input) => {

  
  input.addEventListener('blur', function(event){
    Form.validateInput(event, submitButton);
    Form.checkFormValidity(inputsExcepcionalCancelationForm,submitButton);
    Form.validateChecks(inputsExcepcionalCancelationForm, submitButton);
   
  });
  
  input.addEventListener('change', function(event){
    Form.validateInput(event, submitButton);
    Form.checkFormValidity(inputsExcepcionalCancelationForm,submitButton);
    Form.validateChecks(inputsExcepcionalCancelationForm, submitButton);
   
  });
  
});




/* ========== Enviando el formulario ============*/
excepcionalCancelationForm.addEventListener('submit', function(event){
  event.preventDefault();
  const isChecked = RequestExceptionalCancellation.confirmTerms();
  if(isChecked){
    RequestExceptionalCancellation.getData(excepcionalCancelationForm,idStudent);
  }
})



/* ========== Verificación del proceso de matrícula============*/

const enrollmentBtn = document.getElementById('enrollmentBtn');
enrollmentBtn.addEventListener('click', async function(event){

    event.preventDefault();
    const data = await EnrollmentProcess.verifyEnrollmentProcessStatus();

    if (data.status == "success") {
        window.location.href = 
        "../../../views/students/registration.html";
      } else {
    
        const body = document.querySelector("#warningModal .modal-body");
        const footer = document.querySelector("#warningModal .modal-footer");
        const warningModalLabel = document.getElementById("warningModalLabel");
        warningModalLabel.innerText = "";
        warningModalLabel.innerText = "Proceso de matrícula";
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
        img.src = "../../../../assets/img/icons/clock-icon.png";
        img.alt = "";
        img.className = "animated-icon";
        imgContainer.appendChild(img);
    
        // Crear y agregar título
        const title = document.createElement("p");
        title.className = "fs-4";
        title.textContent =
          "El proceso de matrícula aún no está activo.";
    
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
    
    
})