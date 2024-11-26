
import { Login } from "./modules/request/login.mjs";
import { Applicant} from "./modules/request/Applicant.mjs";

const loginApplicant = document.getElementById('loginApplicant');
loginApplicant.addEventListener('submit',function(event){
    event.preventDefault();
    Login.authApplicant();
}); 