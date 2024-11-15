import {Inscription} from "./modules/Inscription.mjs";
import { RegionalCenter } from "./modules/RegionalCenter.js";
import { Career } from "./modules/Career.js";

const inscriptionButton = document.querySelectorAll('.btn-inscription')
inscriptionButton.forEach(button => {
    button.addEventListener('click', function() {
        RegionalCenter.renderSelectRegionalCenters();
        Inscription.showModal('Inscription-form');
    });
});



const inscriptionForm = document.getElementById('inscriptionForm');
inscriptionForm.addEventListener('submit',function(event){
    event.preventDefault();
    Inscription.getData();
}); 



