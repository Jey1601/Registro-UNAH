import { Sidebar, Table } from "../modules/behavior/support.mjs";
import { Student } from "../modules/request/Student.mjs";

/* ========== Constantes  ============*/
const toggleSidebarButton = document.getElementById("toggleSidebar");
const closeSidebarButton = document.getElementById("closeSidebar");



/* ========== Funcionalidad del sidebar  ============*/
toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);

/* ========== Construcción del sidebar  ============*/
Sidebar.buildSidebar('../../../')

/* ========== Cargando las clases matrículadas  ============*/
window.addEventListener('load', async function(){
   const sections = await Student.getEnrollmentClassSection('20240003');
   
   Table.renderDynamicTable(sections,'viewSections');
   Student.addOptionTableMain('viewSections');
});



const logoutBtn = document.getElementById('logoutBtn');
logoutBtn.addEventListener('click', function(event){
    event.preventDefault();
    Login.logout();
});  
