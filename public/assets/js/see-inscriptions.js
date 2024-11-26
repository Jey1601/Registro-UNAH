import { Applicant } from "./modules/request/Applicant.mjs";
import { Login } from "./modules/request/login.mjs";

window.addEventListener('load', function(){
    Applicant.renderData();

 
});



const logoutBtn = document.getElementById('logoutBtn');
logoutBtn.addEventListener('click', function(event){
    event.preventDefault();
    Login.logout('../../index.html')
});  