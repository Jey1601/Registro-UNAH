import { Sidebar, Table, Search } from "../../modules/behavior/support.mjs";
import { Professor } from "../../modules/request/Professor.mjs";
import { Login } from "../../modules/request/login.mjs";
/* ========== Constantes  ============*/
const toggleSidebarButton = document.getElementById("toggleSidebar");
const closeSidebarButton = document.getElementById("closeSidebar");

//Variable que almacena el usuariol professor
let idProfessor = "";

//botones de descarga
const downloadCSVBtn = document.getElementById('downloadCSV');
const downloadPDFBtn = document.getElementById('downloadPDF');

toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);

Sidebar.buildSidebar("../../../");

window.addEventListener("load", async function () {
  //Obtenemos la ifnromación del token
  const token = sessionStorage.getItem("token"); // Obtén el token del sessionStorage

  if (!token)
    // Si no hay token, no se ejecuta lo demás

    this.window.location.href = "../../../../index.html";
  try {
    const payload = Login.getPayloadFromToken(token);
    idProfessor = payload.username;
  } catch (error) {
    // Si ocurre un error, simplemente no se ejecuta el resto del código.
    console.log(error);
    this.window.location.href = "../../../../index.html";
  }

  const academicCharge = await Professor.getAcademicCharge(idProfessor);

  if (academicCharge[0] != null && academicCharge[1] != null) {
    let combinedCharge = [...academicCharge[0], ...academicCharge[1]];
    Table.renderDynamicTable(combinedCharge, "viewSections");
  } else if (academicCharge[0] != null) {
    Table.renderDynamicTable(academicCharge[0], "viewSections");
  } else if (academicCharge[1] != null) {
    Table.renderDynamicTable(academicCharge[1], "viewSections");
  }

  Search.onInputChange("searchSection", "viewDataSectionsBody");



    /* ========== Agregando los eventos a los botones ============*/
    downloadCSVBtn.addEventListener('click', function(){
        
        Professor.getAcademicChargeCSV(idProfessor);
    }) 


    downloadPDFBtn.addEventListener('click', function(){
        Professor.getAcademicChargePDF(idProfessor);
    }) 

});


