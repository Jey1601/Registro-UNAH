import { Sidebar, Table , Search} from "../../modules/behavior/support.mjs";
import { RegionalCenter } from "../../modules/request/RegionalCenter.mjs";
import { Career } from "../../modules/request/Career.mjs";
import { Professor } from "../../modules/request/Professor.mjs";
import { Login } from "../../modules/request/login.mjs";


/* ========== Constantes  ============*/
const toggleSidebarButton = document.getElementById("toggleSidebar");
const closeSidebarButton = document.getElementById("closeSidebar");


const regionalCenters = document.getElementById('regionalCenters');
const career = document.getElementById('undegraduates');
let idProfessor ='';

toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);

Sidebar.buildSidebar('../../../')


window.addEventListener('load', async function(){

    //Obtenemos la ifnromación del token
  const token = sessionStorage.getItem("token"); // Obtén el token del sessionStorage

   
  try {
    const payload = Login.getPayloadFromToken(token);
    idProfessor = payload.username;
    console.log(idProfessor);

  } catch (error) {
    // Si ocurre un error, simplemente no se ejecuta el resto del código.
    console.log(error);
    this.window.location.href = "../../../../index.html";
  }


    await RegionalCenter.renderSelectRegionalCenters('regionalCenters','Coordinator' );
    await Career.updateFirstOption('undegraduates', 'regionalCenters', 'Coordinator');

    const students =  await Professor.fetchStudentsByRegionalCenterUndergraduate(idProfessor, regionalCenters.value, career.value);
    Table.renderDynamicTable(students,'viewStudents' );

    Professor.addOptionGrades('viewStudents',idProfessor);
    Search.onInputChange('search','viewStudents');
    Search.onInputChange('searchClass','viewHistorial');
})

regionalCenters.addEventListener('change', async function(){
    await Career.updateFirstOption('undegraduates', 'regionalCenters', 'Coordinator');
    const students =  await Professor.fetchStudentsByRegionalCenterUndergraduate(idProfessor, regionalCenters.value, career.value);
    Table.renderDynamicTable(students,'viewStudents' );
    Professor.addOptionGrades('viewStudents',idProfessor);
})


career.addEventListener('change', async function(){
   
   const students = await Professor.fetchStudentsByRegionalCenterUndergraduate(idProfessor, regionalCenters.value, career.value);
   Table.renderDynamicTable(students,'viewStudents' );
   Professor.addOptionGrades('viewStudents',idProfessor);
})
