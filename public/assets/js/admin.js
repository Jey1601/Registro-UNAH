import { Applicant } from "./modules/Applicant.js";

const  viewInscriptionsBtn = document.getElementById('viewInscriptionsBtn');
viewInscriptionsBtn.addEventListener('click', function(){
    Applicant.viewData();
})