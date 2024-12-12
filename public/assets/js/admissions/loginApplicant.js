
import { Login } from "../modules/request/login.mjs";


const loginApplicant = document.getElementById('loginApplicant');
loginApplicant.addEventListener('submit',function(event){
    event.preventDefault();
    Login.authApplicant();
}); 