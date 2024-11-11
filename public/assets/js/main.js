import {Inscription} from "./modules/Inscription.js";

const inscriptionForm = document.getElementById('inscriptionForm');
inscriptionForm.addEventListener('submit',function(event){
    event.preventDefault();
    Inscription.getData();
}); 

