
import { Modal, Form, Alert } from "./modules/behavior/support.mjs";
import { Applicant } from "./modules/request/Applicant.mjs";
import { Login } from "./modules/request/login.mjs";

const dataEditionForm = document.getElementById('dataEditionForm');
const inputsDataEditionForm= Array.from(dataEditionForm.elements);
const dataEditionButton = document.getElementById('dataEditionButton');

dataEditionForm.addEventListener('submit', function(event){
    event.preventDefault();
    Applicant.getData();
});

inputsDataEditionForm.forEach((input) => {

  
  input.addEventListener('blur', function(event){
    Form.validateInput(event, dataEditionButton);
    Form.checkFormValidity(inputsDataEditionForm,dataEditionButton);
  });
 
  
});

window.addEventListener('load', function(){
      const token = sessionStorage.getItem('token'); // Obtén el token del sessionStorage
      const payload = Login.getPayloadFromToken(token);
      const applicantID = payload.userApplicant; 

    Applicant.renderDataToEdit(applicantID);
});




const logoutBtn = document.getElementById('logoutBtn');
logoutBtn.addEventListener('click', function(event){
    event.preventDefault();
    Login.logout('../../index.html')
});  


