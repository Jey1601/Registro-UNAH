import { Sidebar } from "../../modules/behavior/support.mjs";
import { AcademicPlanning } from "../../modules/request/AcademicPlanning.mjs";
import { RegionalCenter } from "../../modules/request/RegionalCenter.mjs";
const toggleSidebarButton = document.getElementById("toggleSidebar");
const closeSidebarButton = document.getElementById("closeSidebar");


toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);

Sidebar.buildSidebar('../../../')


window.addEventListener('load', function(event){
    
    event.preventDefault();
    //Aquí se debe obtener el usuario del profesor del token

     /*const token = sessionStorage.getItem('token'); // Obtén el token del sessionStorage
        const payload = Login.getPayloadFromToken(token);
        const username_user_professor = payload.userProfessor; */

    const IdProfessor = 1;
    AcademicPlanning.regionalCentersAcademicPlanning(IdProfessor);
    RegionalCenter.renderSelectRegionalCentersByProfessor('ademicPlanningCenter',IdProfessor);

})
