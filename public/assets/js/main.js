import {Inscription} from "./modules/request/Inscription.mjs";
import { RegionalCenter } from "./modules/request/RegionalCenter.mjs";
import { Modal, Form, Alert, File } from "./modules/behavior/support.mjs";
import { Login } from "./modules/request/login.mjs";
import { AdmissionProccess } from "./modules/request/AdmissionProcces.mjs";

const inscriptionButton = document.querySelectorAll('.btn-inscription')
inscriptionButton.forEach(button => {
    button.addEventListener('click', function() {

        AdmissionProccess.verifyInscriptionAdmissionProcess();
    });
});



const inscriptionForm = document.getElementById('inscriptionForm');
inscriptionForm.addEventListener('submit',function(event){
    event.preventDefault();

    Modal.showModal('modalEmailCodeVerification');
    verifyEmailBtn.disabled = true;
    Inscription.setConfirmationEmailApplicants();

   
}); 

const inputsInscriptionForm = Array.from(inscriptionForm.elements);
const submitButton = document.getElementById('inscriptionButton');

inputsInscriptionForm.forEach((input) => {

  
  input.addEventListener('blur', function(event){
    Form.validateInput(event, submitButton);
    Form.checkFormValidity(inputsInscriptionForm,submitButton);
  });
 
  
});

/*const loginAdmissionsButton = document.getElementById('loginAdmissionsButton');

loginAdmissionsButton.addEventListener('click', () => {
  Modal.showModal('loginModalAdmissions');
});*/



/*const loginAdmissions= document.getElementById('loginAdmissions');
const btnLogin = document.getElementById('btnLogin');
loginAdmissions.addEventListener('submit', function(event){
    event.preventDefault();
    btnLogin.disabled = true;
    Modal.hideModal('loginModalAdmissions');
    Login.authAdmisionAdmin();
    
})*/

const emailCodeVerification = document.getElementById('emailCodeVerification');
emailCodeVerification.addEventListener('keyup',function(){
  if(emailCodeVerification.value.length == 5){
    verifyEmailBtn.disabled = false;
  }
})
const verifyEmailBtn = document.getElementById('verifyEmailBtn');
verifyEmailBtn.addEventListener('click', function(event){
  event.preventDefault();
  verifyEmailBtn.disabled = true;
  Inscription.getConfirmationEmailApplicants();

}) 


/* ========== Validando el formato de los input ============*/

document.getElementById('applicantCertificate').addEventListener('change', function(event) {
  const fileInput = event.target;  // Referencia al input
  const file = fileInput.files[0]; // Obtener el archivo seleccionado
  
  File.validateFile(file, fileInput);

});

document.getElementById('applicantIdDocument').addEventListener('change', function(event) {
  const fileInput = event.target;  // Referencia al input
  const file = fileInput.files[0]; // Obtener el archivo seleccionado
  
  File.validateFile(file, fileInput);

});


// Escuchar los clics en los botones de la lista de login
document.querySelectorAll('.dropdown-item').forEach(button => {
  button.addEventListener('click', function() {
      // Comprobamos el texto del botón para determinar qué valor asignar
      switch (this.textContent.trim()) {
          case 'Gestión estratégica':
              Login.updateUserType('strategicAdmin');
           
              break;
          case 'Facultades':
              Login.updateUserType('facultyAdmin');
            
              break;
          case 'Admisiones':
            Login.updateUserType('admissionAdmin');

         
              break;
          case 'DIPP':
            Login.updateUserType('dippAdmin');
           
              break;
          default:
              break;

      }


      //Se llama a la modal de login 
      Modal.showModal('loginModal');  
  });
});


const loginForm = document.getElementById("loginForm");
const btnLoginGeneral = document.getElementById('btnLoginAdministration');
// Función para autenticar según el tipo de usuario
loginForm.onsubmit = function(event) {
  event.preventDefault(); // Prevenir el envío del formulario

  const username = document.getElementById("username").value;
  const password = document.getElementById("password").value;
  const userType = document.getElementById("userType").value;
  btnLoginGeneral.disabled = true;
  // Ejecutar la función correspondiente según el tipo de usuario
  switch (userType) {
      case 'strategicAdmin':
          Modal.hideModal('loginModal');
          authenticateAdmin(username, password);
          break;
      case 'facultyAdmin':
          Modal.hideModal('loginModal');
          btnLoginGeneral.disabled = false;
          authenticateStudent(username, password);
          break;
      case 'admissionAdmin':
          
          Modal.hideModal('loginModal');
          Login.authAdmisionAdmin(username,password);
          break;
      case 'dippAdmin':
        authenticateTeacher(username, password);
           break;
      default:
          Alert.display('warning','Algo salio mal','Invalid user type');
  }
}