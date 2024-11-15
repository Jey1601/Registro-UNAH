
import { Login } from "./modules/login.mjs";

const loginApplicant = document.getElementById('loginApplicant');
loginApplicant.addEventListener('submit',function(event){
    event.preventDefault();
    Login.getDataApplicant();
}); 