import { Applicant } from "../../modules/request/Applicant.mjs";
import { Login } from "../../modules/request/login.mjs";
import { Form } from "../../modules/behavior/support.mjs";

/**
 *
 * @author Jeyson Espinal (20201001015)
 * @created 2024-11
 */

const path = "../../../../";
window.addEventListener("load", function () {
  const token = sessionStorage.getItem("token"); // Obtén el token del sessionStorage
  let access = [];

  if (token) {
    const payload = Login.getPayloadFromToken(token);
    access = payload.accessArray;

    if (
      access.includes("lwx50K7f") ||
      access.includes("rllHaveq") ||
      access.includes("IeMfti20")
    ) {
      Applicant.renderData(access);
    } else {
      this.window.location.href = path + "index.html";
    }
  }
});

const logoutBtn = document.getElementById("logoutBtn");
logoutBtn.addEventListener("click", function (event) {
  event.preventDefault();
  Login.logout();
});

const dataApplication = document.getElementById("dataApplication");
dataApplication.addEventListener("change", function (event) {
  Form.changeActionByChecks("checkButton", "Aprobar", "Rechazar");
});

dataApplication.addEventListener("submit", function (event) {
  event.preventDefault();
  Applicant.getChecks();
});

const downloadInscriptionsBtn = document.getElementById(
  "downloadInscriptionsCsv"
);
downloadInscriptionsBtn.addEventListener("click", function () {
  // Redirige al usuario a la URL del endpoint que genera el CSV
  window.location.href = path + "api/get/applicant/applicantDownloadCSV.php";
});
