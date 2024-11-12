import {Inscription} from "./modules/Inscription.js";
import {AnimationLoad} from "./modules/animations.js";
import { Login } from "./modules/login.js";

window.addEventListener('load', function() {
    AnimationLoad.hideSun();
});  

const inscriptionForm = document.getElementById('inscriptionForm');
inscriptionForm.addEventListener('submit',function(event){
    event.preventDefault();
    Inscription.getData();
}); 

/*const loginAdmissionsButton =document.getElementById('loginAdmissionsButton');
loginAdmissionsButton.addEventListener('click',function(){
    Login.showLoginAdmissions();
});

const loginStudentsButton =document.getElementById('loginStudentsButton');
    loginStudentsButton.addEventListener('click',function(){
    Login.showLoginEstudents();
});*/

