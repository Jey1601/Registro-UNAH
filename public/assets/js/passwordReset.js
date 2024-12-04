import { Password } from "./modules/behavior/password.mjs";

const  password = document.getElementById('password');
const buttonPassword = password.nextElementSibling;

const  passwordVerification = document.getElementById('passwordVerification');
const buttonPasswordVerification = passwordVerification.nextElementSibling;

buttonPassword.addEventListener('click', function(){
    Password.togglePassword('password');
})

buttonPasswordVerification.addEventListener('click', function(){
    Password.togglePassword('passwordVerification');
})

password.addEventListener('input', function(){
    Password.verifyPassword('password','passwordVerification','resetButton','Formatear','No son iguales');
})

passwordVerification.addEventListener('input', function(){
    
    Password.verifyPassword('password','passwordVerification','resetButton','Formatear','No son iguales');
})



