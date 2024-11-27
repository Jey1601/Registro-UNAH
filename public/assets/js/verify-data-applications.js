 import { Applicant } from "./modules/request/Applicant.mjs";
import { Login } from "./modules/request/login.mjs";
import { Form } from "./modules/behavior/support.mjs";

window.addEventListener('load', function(){
    Applicant.renderData(3);

 
});



const logoutBtn = document.getElementById('logoutBtn');
logoutBtn.addEventListener('click', function(event){
    event.preventDefault();
    Login.logout('../../index.html')
});  


const dataApplication = document.getElementById('dataApplication');
dataApplication.addEventListener('change', function(event){
    Form.changeActionByChecks('dataApplication','checkButton','Aprobar','Rechazar','1','2');

})

dataApplication.addEventListener('submit', function(event){
    event.preventDefault();
    console.log('se ha enviado el formulario')

})


const downloadInscriptionsBtn = document.getElementById('downloadInscriptionsBtn');
downloadInscriptionsBtn.addEventListener('click', function() {
   // Redirige al usuario a la URL del endpoint que genera el CSV
   window.location.href = "../../../api/get/applicant/applicantDownloadCSV.php";
});