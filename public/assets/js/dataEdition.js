
import { Modal, Form, Alert } from "./modules/behavior/support.mjs";
import { Applicant } from "./modules/request/Applicant.mjs";

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
    
   
    Applicant.renderDataToEdit('0801200119258');
});




const logoutBtn = document.getElementById('logoutBtn');
logoutBtn.addEventListener('click', function(event){
    event.preventDefault();
    Login.logout('../../index.html')
});  


