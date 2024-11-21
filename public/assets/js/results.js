import { Applicant } from "./modules/request/Applicant.mjs";
import { Results } from "./modules/request/results.mjs";
import { Login } from "./modules/request/login.mjs";

window.addEventListener('load', function(){
    const token = sessionStorage.getItem('token'); // Obtén el token del sessionStorage

    if (token) {
        const payload = Login.getPayloadFromToken(token);
        const applicantID = payload.applicantID; 
        Applicant.renderResults(applicantID);
    } 
   
});


const resultsForm = document.getElementById('resultsForm');
resultsForm.addEventListener('submit',function(event){
    event.preventDefault();
    Results.verify();
})

const selectionCorrectBtn = document.getElementById('selectionCorrectBtn');
        selectionCorrectBtn.addEventListener('click', function(event){
            event.preventDefault();
            Results.getSelection();
} )




const logoutBtn = document.getElementById('logoutBtn');
logoutBtn.addEventListener('click', function(event){
    event.preventDefault();
    Login.logout('../../index.html')
});  