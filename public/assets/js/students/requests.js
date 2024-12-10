import { Modal, Form, File, Table } from "../modules/behavior/support.mjs";
import { modalTemplates } from "../modules/templates/students/requests/modalTemplates.mjs";
import { Student } from "../modules/request/Student.mjs";
import { Sidebar } from "../modules/behavior/support.mjs";

/* ========== Constantes  ============*/
const toggleSidebarButton = document.getElementById("toggleSidebar");
const closeSidebarButton = document.getElementById("closeSidebar");
const termsBtn = document.getElementById('termsBtn');
const excepcionalCancelationForm = document.getElementById('excepcionalCancelationForm');
const inputsExcepcionalCancelationForm = Array.from(excepcionalCancelationForm.elements);
const submitButton = document.getElementById('sendButton');

/* ========== Side Bar ============*/
toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);

Sidebar.buildSidebar('../../../')


/* ========== Cargando las clases matrículadas en caso de cancelación excepcional ============*/
window.addEventListener('load', async function(){
  const sections = await Student.getEnrollmentClassSection('20240003');
  
  Table.renderDynamicTable(sections,'viewSections');
  Student.addOptionTableRequestCancellation('viewSections');
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



inputsExcepcionalCancelationForm.forEach((input) => {

  
  input.addEventListener('blur', function(event){
    Form.validateInput(event, submitButton);
    Form.checkFormValidity(inputsExcepcionalCancelationForm,submitButton);
  });
 
  
});








