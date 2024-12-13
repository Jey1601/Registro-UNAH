import { Login } from "../../modules/request/login.mjs";

   /**
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-11
   */

const downloadApplicantAdmittedBtn = document.getElementById('downloadApplicantAdmittedBtn');
downloadApplicantAdmittedBtn.addEventListener('click', function() {
   window.location.href = "../../../../../api/get/applicant/applicantDownloadAdmittedCSV.php";
});

const logoutBtn = document.getElementById('logoutBtn');
logoutBtn.addEventListener('click', function(event){
    event.preventDefault();
    Login.logout()
});  
