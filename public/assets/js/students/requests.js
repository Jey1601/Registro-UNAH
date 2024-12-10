import { Modal, Form, File, Table } from "../modules/behavior/support.mjs";
import { modalTemplates } from "../modules/templates/students/requests/modalTemplates.mjs";
import { Student } from "../modules/request/Student.mjs";
import { Sidebar } from "../modules/behavior/support.mjs";
import { RequestExceptionalCancellation } from "../modules/request/RequestExceptionalCancellation.mjs";
import { Login } from "../modules/request/login.mjs";
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

   if (!token)  // Si no hay token, no se ejecuta lo demás
   
   this.window.location.href ='../../../index.html'
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



/* ========== logout ============*/
const logoutBtn = document.getElementById('logoutBtn');
logoutBtn.addEventListener('click', function(event){
    event.preventDefault();
    Login.logout();
});  
