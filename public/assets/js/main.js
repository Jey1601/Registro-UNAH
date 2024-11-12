import {Inscription} from "./modules/Inscription.js";
import {AnimationLoad} from "./modules/animations.js";


window.addEventListener('load', function() {
    AnimationLoad.hideSun();
});  

const inscriptionForm = document.getElementById('inscriptionForm');
inscriptionForm.addEventListener('submit',function(event){
    event.preventDefault();
    Inscription.getData();
}); 

