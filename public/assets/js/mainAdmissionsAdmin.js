import { Applicant } from "./modules/request/Applicant.mjs";

const  viewInscriptionsBtn = document.getElementById('viewInscriptionsBtn');
viewInscriptionsBtn.addEventListener('click', function(){
   window.location.href='../administration/see-inscriptions.html';
})



const downloadInscriptionsBtn = document.getElementById('downloadInscriptionsBtn');
downloadInscriptionsBtn.addEventListener('click', function() {
   // Redirige al usuario a la URL del endpoint que genera el CSV
   window.location.href = "../../../api/get/applicant/applicantDownloadCSV.php";
});

const downloadApplicantAdmittedBtn = document.getElementById('downloadApplicantAdmittedBtn');
downloadApplicantAdmittedBtn.addEventListener('click', function() {
   window.location.href = "../../../api/get/applicant/applicantDownloadAdmittedCSV.php";
});
