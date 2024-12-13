import { Form } from "./modules/behavior/support.mjs";
import { Student } from "./modules/request/Student.mjs";


   /**
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-11
   */

const emailButton = document.getElementById('emailButton');
const email = document.getElementById('Email');
const form = document.getElementById('passwordResetRequest');

emailButton.addEventListener('click', function(event){
    event.preventDefault();
    Student.requestPasswordReset(email.value);
    
})

email.addEventListener('blur', function(event){
    console.log('se busca');
    Form.validateInput(event);
    Form.checkFormValidity(form.querySelectorAll('input'),emailButton,'Enviar','Verifica tu correo');
})

email.addEventListener('keyup', function(event){
    console.log('se busca');
    Form.validateInput(event);
    Form.checkFormValidity(form.querySelectorAll('input'),emailButton,'Enviar','Verifica tu correo');
})


