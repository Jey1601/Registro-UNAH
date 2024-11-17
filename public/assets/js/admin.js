import { Applicant } from "./modules/Applicant.mjs";

const  viewInscriptionsBtn = document.getElementById('viewInscriptionsBtn');
viewInscriptionsBtn.addEventListener('click', function(){
    Applicant.renderData();
})