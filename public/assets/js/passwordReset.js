import { Password } from "./modules/behavior/password.mjs";
import { Student } from "./modules/request/Student.mjs";

const  password = document.getElementById('password');
const buttonPassword = password.nextElementSibling;

const  passwordVerification = document.getElementById('passwordVerification');
const buttonPasswordVerification = passwordVerification.nextElementSibling;
const resetButton = document.getElementById('resetButton');
const token = document.getElementById('token');
window.addEventListener('load', function(){
    // Función para obtener el valor de un parámetro de la URL
function getURLParameter(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
  }
  
  // Obtener el valor del parámetro 'token' de la URL
  const token = getURLParameter('token');
  
  // Si el parámetro existe, asignarlo al campo oculto
  if (token) {
    document.getElementById('token').value = token;
   
  }
})


buttonPassword.addEventListener('click', function(){
    Password.togglePassword('password');
})

buttonPasswordVerification.addEventListener('click', function(){
    Password.togglePassword('passwordVerification');
})

password.addEventListener('input', function(){
    Password.verifyPassword('password','passwordVerification','resetButton','Formatear','No son iguales');
})
password.addEventListener('blur', function(){
    Password.verifyPassword('password','passwordVerification','resetButton','Formatear','No son iguales');
})


passwordVerification.addEventListener('input', function(){
    
    Password.verifyPassword('password','passwordVerification','resetButton','Formatear','No son iguales');
})

passwordVerification.addEventListener('blur', function(){
    
    Password.verifyPassword('password','passwordVerification','resetButton','Formatear','No son iguales');
})


resetButton.addEventListener('click', function(event){
        event.preventDefault();
         console.log(password.value);
        console.log(token.value);
    Student.resetPassword(token.value, password.value);
})

