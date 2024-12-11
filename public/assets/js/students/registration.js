import { Sidebar } from "../modules/behavior/support.mjs";
import { EnrollmentProcess } from "../modules/request/EnrollmentProcess.mjs";
/* ========== Constantes  ============*/
const toggleSidebarButton = document.getElementById("toggleSidebar");
const closeSidebarButton = document.getElementById("closeSidebar");
let idStudent = "20240002123";

const departmentSelect = document.getElementById("departmentSelect");
const classSelect = document.getElementById("classSelect");

toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);

Sidebar.buildSidebar("../../../");

window.addEventListener("load", async function () {
  const data = await EnrollmentProcess.getPendingClassesStudent(idStudent);
  EnrollmentProcess.populateDepartments(data, departmentSelect);

  // Evento para cambiar las clases cuando se selecciona un departamento
  departmentSelect.addEventListener("change", () => {
    const selectedDepartment = departmentSelect.value;
    EnrollmentProcess.populateClasses(data, selectedDepartment, classSelect);
  });

  classSelect.addEventListener('change', ()=>{
    const selectedClass = parseInt(classSelect.value,10);
    const classes = EnrollmentProcess.getClassSectionsForStudent(idStudent,selectedClass);

  });

});
