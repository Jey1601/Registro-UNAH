import {Inscription} from "./modules/Inscription.js";



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

