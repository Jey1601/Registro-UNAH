import {Inscription} from "./modules/request/Inscription.mjs";
import { RegionalCenter } from "./modules/request/RegionalCenter.mjs";
import { Modal, Form, Alert, File } from "./modules/behavior/support.mjs";
import { Login } from "./modules/request/login.mjs";
import { AdmissionProccess } from "./modules/request/AdmissionProcces.mjs";
import { Password } from "./modules/behavior/password.mjs";



/* ========== Validando el proceso de inscripción ============*/

const inscriptionButton = document.querySelectorAll('.btn-inscription')
inscriptionButton.forEach(button => {
    button.addEventListener('click', function() {

        AdmissionProccess.verifyInscriptionAdmissionProcess();
    });
});


const  password = document.getElementById('password');
const buttonPassword = password.nextElementSibling;
buttonPassword.addEventListener('click', function(){
  Password.togglePassword('password');
})



/* ========== Validando el código del correo ============*/

const inscriptionForm = document.getElementById('inscriptionForm');
inscriptionForm.addEventListener('submit',function(event){
    event.preventDefault();

    Modal.showModal('modalEmailCodeVerification');
    verifyEmailBtn.disabled = true;
    Inscription.setConfirmationEmailApplicants();

   
}); 

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



const inputsInscriptionForm = Array.from(inscriptionForm.elements);
const submitButton = document.getElementById('inscriptionButton');

inputsInscriptionForm.forEach((input) => {

  
  input.addEventListener('blur', function(event){
    Form.validateInput(event, submitButton);
    Form.checkFormValidity(inputsInscriptionForm,submitButton);
  });
 
  
});


/* ========== Desplegando logins ============*/

// Obtener referencias a los elementos del formulario y el botón de login
const loginForm = document.getElementById("loginForm");
const btnLogin = document.getElementById('btnLogin');

// Función que se ejecuta al enviar el formulario de login
loginForm.onsubmit = function(event) {
  event.preventDefault(); // Prevenir el envío del formulario para manejarlo manualmente

  // Obtener los valores de los campos del formulario
  const username = document.getElementById("username").value;
  const password = document.getElementById("password").value;
  const userType = document.getElementById("userType").value;
  
  // Deshabilitar el botón de login mientras se procesa
 // btnLogin.disabled = true;

  // Seleccionar la acción según el tipo de usuario
  switch (userType) {
      case 'strategicAdmin':
          Modal.hideModal('loginModal'); // Cerrar la modal de login
          // Aquí llamar la función específica para autenticar este tipo de usuario
          break;
      case 'facultyAdmin':
          Modal.hideModal('loginModal'); // Cerrar la modal de login
          Login.authFacultyAdmin(username, password);
          break;
      case 'admissionAdmin':
          Modal.hideModal('loginModal'); // Cerrar la modal de login
          Login.authAdmisionAdmin(username, password); // Llamar la función para autenticar a un administrador de admisiones
          break;
      case 'dippAdmin':
          Modal.hideModal('loginModal'); // Cerrar la modal de login
          Login.authDIIPAdmin(username, password);
          break;
      case 'professor':
        Modal.hideModal('loginModal');
         // Cerrar la modal de login
         const usernameAsInteger = parseInt(username, 10);
        Login.authProfessor(usernameAsInteger, password);
        break;
      case 'student':
        Modal.hideModal('loginModal'); // Cerrar la modal de login
        Login.authStudent(username, password)
        break;
      default:
          // Mostrar una alerta en caso de un tipo de usuario inválido
          Alert.display('warning', 'Algo salió mal', 'Invalid user type');
  }
}

// Escuchar clics en los botones que abren el formulario de login
document.querySelectorAll('.navbar-menu-item button, .dropdown-item').forEach(button => {
  button.addEventListener('click', function() {
    // Cambiar estilos del botón de login
    btnLogin.classList.add('btn-blue'); // Agregar clase para color azul
    btnLogin.classList.remove('btn-yellow'); // Remover clase para color amarillo
    username.placeholder = 'Usuario..'; // Establecer placeholder predeterminado para el campo de usuario

    // Seleccionar la acción según el texto del botón clickeado
    switch (this.textContent.trim()) {
        case 'Gestión estratégica':
            Login.updateUserType('strategicAdmin'); // Actualizar tipo de usuario
            Modal.showModal('loginModal'); // Mostrar la modal de login
            break;
        case 'Facultades':
            Login.updateUserType('facultyAdmin'); // Actualizar tipo de usuario
            Modal.showModal('loginModal'); // Mostrar la modal de login
            break;
        case 'Admisiones':
            Login.updateUserType('admissionAdmin'); // Actualizar tipo de usuario
            Modal.showModal('loginModal'); // Mostrar la modal de login
            break;
        case 'DIPP':
            Login.updateUserType('dippAdmin'); // Actualizar tipo de usuario
            Modal.showModal('loginModal'); // Mostrar la modal de login
            break;
        case 'Estudiantes':
            Login.updateUserType('student'); // Actualizar tipo de usuario
            btnLogin.classList.remove('btn-blue'); // Cambiar estilo del botón
            btnLogin.classList.add('btn-yellow'); // Cambiar estilo del botón
            username.placeholder = 'Número de cuenta..'; // Cambiar placeholder para estudiantes
            Modal.showModal('loginModal'); // Mostrar la modal de login
            break;
        case 'Docentes':
            Login.updateUserType('professor'); // Actualizar tipo de usuario
            Modal.showModal('loginModal'); // Mostrar la modal de login
            break;
        default:
            // Si no coincide con ningún caso, no hacer nada
            break;
    }
  });
});