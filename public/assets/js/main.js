import {Inscription} from "./modules/request/Inscription.mjs";
import { RegionalCenter } from "./modules/request/RegionalCenter.mjs";
import { Modal, Form, Alert } from "./modules/behavior/support.mjs";
import { Login } from "./modules/request/login.mjs";

const inscriptionButton = document.querySelectorAll('.btn-inscription')
inscriptionButton.forEach(button => {
    button.addEventListener('click', function() {
        RegionalCenter.renderSelectRegionalCenters();
        Modal.showModal('Inscription-form');
    });
});


const inscriptionForm = document.getElementById('inscriptionForm');
inscriptionForm.addEventListener('submit',function(event){
    event.preventDefault();

    Modal.showModal('modalEmailCodeVerification');

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




