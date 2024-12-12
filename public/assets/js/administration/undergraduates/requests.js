import { Professor } from "../../modules/request/Professor.mjs";
import { Alert, Sidebar, Table } from "../../modules/behavior/support.mjs";
import { Login } from "../../modules/request/login.mjs";
import { RequestExceptionalCancellation } from "../../modules/request/RequestExceptionalCancellation.mjs";
/* ========== Constantes  ============*/
const toggleSidebarButton = document.getElementById("toggleSidebar");
const closeSidebarButton = document.getElementById("closeSidebar");

//Variable que almacena el usuariol professor
let idProfessor = "";


toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);

Sidebar.buildSidebar('../../../')


window.addEventListener('load', async function(){

     //Obtenemos la ifnromación del token
  const token = sessionStorage.getItem("token"); // Obtén el token del sessionStorage

 
  try {
    const payload = Login.getPayloadFromToken(token);
    idProfessor = payload.username;
  } catch (error) {
    // Si ocurre un error, simplemente no se ejecuta el resto del código.
    console.log(error);
    this.window.location.href = "../../../../index.html";
  }


   const solicitudes = await Professor.getRequestsCancellationExceptional(idProfessor);
   if(solicitudes.length == 0 ){
    Alert.display('info','Todo en orden', 'No tiene solicitudes pendientes','../../../../');
   }
   Table.renderDynamicTable(solicitudes,'viewPendingSolicitudes');
   RequestExceptionalCancellation.addOptionView('viewPendingSolicitudes');
})