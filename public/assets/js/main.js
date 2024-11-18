import {Inscription} from "./modules/Inscription.mjs";
import { RegionalCenter } from "./modules/RegionalCenter.mjs";
import { Modal } from "./modules/support.mjs";

const inscriptionButton = document.querySelectorAll('.btn-inscription')
inscriptionButton.forEach(button => {
    button.addEventListener('click', function() {
        RegionalCenter.renderSelectRegionalCenters();
        Modal.showModal('Inscription-form');
    });
});


const inscriptionForm = document.getElementById('inscriptionForm');
inscriptionForm.addEventListener('submit',function(event){
    event.preventDefault();
    Inscription.getData();
}); 






