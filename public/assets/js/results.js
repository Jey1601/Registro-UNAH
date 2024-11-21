import { Applicant } from "./modules/request/Applicant.mjs";
import { Results } from "./modules/request/results.mjs";

window.addEventListener('load', function(){
    let id_applicant = sessionStorage.getItem('id_applicant');
    Applicant.renderResults(id_applicant);
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


const closeAndRedirect = document.getElementById('closeAndRedirect');
closeAndRedirect.addEventListener('click', function(){
    window.location.href = "../../index.html";
})