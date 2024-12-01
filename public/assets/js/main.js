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
    Inscription.setConfirmationEmailApplicants();
   /* submitButton.classList.remove('wrong-form');
    submitButton.innerText = "Enviar";
    Inscription.getData();*/
  
   
}); 

const inputsInscriptionForm = Array.from(inscriptionForm.elements);
const submitButton = document.getElementById('inscriptionButton');

inputsInscriptionForm.forEach((input) => {

  
  input.addEventListener('blur', function(event){
    Form.validateInput(event, submitButton);
    Form.checkFormValidity(inputsInscriptionForm,submitButton);
  });
 
  
});




const loginAdmissions= document.getElementById('loginAdmissions');
loginAdmissions.addEventListener('submit', function(event){
    event.preventDefault();
    Login.authAdmisionAdmin();
})


const verifyEmailBtn = document.getElementById('verifyEmailBtn');
verifyEmailBtn.addEventListener('click', function(event){
  event.preventDefault();
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