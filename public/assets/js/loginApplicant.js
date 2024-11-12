
import { Login } from "./modules/login.js";

const loginApplicant = document.getElementById('loginApplicant');
loginApplicant.addEventListener('submit',function(event){
    event.preventDefault();
    Login.getDataApplicant();
}); 