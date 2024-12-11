import { Sidebar, Table } from "../modules/behavior/support.mjs";
import { Student } from "../modules/request/Student.mjs";
import { Login } from "../modules/request/login.mjs";
/* ========== Constantes  ============*/
const toggleSidebarButton = document.getElementById("toggleSidebar");
const closeSidebarButton = document.getElementById("closeSidebar");
let idStudent  = '';


/* ========== Funcionalidad del sidebar  ============*/
toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);

/* ========== Construcción del sidebar  ============*/
Sidebar.buildSidebar('../../../')

/* ========== Cargando las clases matrículadas  ============*/
window.addEventListener('load', async function(){


   const token = sessionStorage.getItem('token'); // Obtén el token del sessionStorage

   if (!token)  // Si no hay token, no se ejecuta lo demás
   
   this.window.location.href ='../../../index.html'
  try {
    
    const payload = Login.getPayloadFromToken(token);
    idStudent = payload.username;
      
  } catch (error) {
    // Si ocurre un error, simplemente no se ejecuta el resto del código.
    console.log(error);
    this.window.location.href ='../../../index.html'
  }

  const sections = await Student.getEnrollmentClassSection(idStudent);

   
   Table.renderDynamicTable(sections,'viewSections');
   Student.addOptionTableMain('viewSections');
});



const logoutBtn = document.getElementById('logoutBtn');
logoutBtn.addEventListener('click', function(event){
    event.preventDefault();
    Login.logout();
});  
