import {Inscription} from "./modules/request/Inscription.mjs";
import { RegionalCenter } from "./modules/request/RegionalCenter.mjs";
import { Modal } from "./modules/behavior/support.mjs";
import { Login } from "./modules/request/login.mjs";
import { AdmissionAdmin } from "./modules/request/loginAdmissionAdmin.mjs";

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

const loginAdmissions= document.getElementById('loginAdmissions');
loginAdmissions.addEventListener('submit', function(event){
    event.preventDefault();
    Login.authRequestAdmissionAdmin();
})

const input_user = document.getElementById('admissionsUser');
const input_password = document.getElementById('admissionsPassword');
const btn_login = document.getElementById('btnLogin');
btn_login.addEventListener('click', function(event) {
    //event.preventDefault();
    AdmissionAdmin.authentication(input_user, input_password);
});



