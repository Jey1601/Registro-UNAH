import { Login } from "./modules/request/login.mjs";
const downloadApplicantAdmittedBtn = document.getElementById('downloadApplicantAdmittedBtn');
downloadApplicantAdmittedBtn.addEventListener('click', function() {
   window.location.href = "../../api/get/applicant/applicantDownloadAdmittedCSV.php";
});

const logoutBtn = document.getElementById('logoutBtn');
logoutBtn.addEventListener('click', function(event){
    event.preventDefault();
    Login.logout('../../../public/index.html')
});  
