import { Sidebar, Form, Table } from "../../modules/behavior/support.mjs";
import { AcademicPlanning } from "../../modules/request/AcademicPlanning.mjs";
import { RegionalCenter } from "../../modules/request/RegionalCenter.mjs";
import { Career } from "../../modules/request/Career.mjs";
import { Class } from "../../modules/request/Class.mjs";
import {  Building } from "../../modules/request/Building.mjs";
import { Classroom } from "../../modules/request/Classroom.mjs";
import { Schedule } from "../../modules/request/Schedules.mjs";
import { Professor } from "../../modules/request/Professor.mjs";
import { Login } from "../../modules/request/login.mjs";

/* ========== Constantes  ============*/
const toggleSidebarButton = document.getElementById("toggleSidebar");
const closeSidebarButton = document.getElementById("closeSidebar");
//Select de centros regionales
const academicPlanningCenterSelect = document.getElementById('ademicPlanningCenter');
//Jefe de departamento
const idProfessor = 1;
const departmentId = 9;
 //Aquí se debe obtener el usuario del profesor del token
//Aquí se debe obtener el departamento al que pertenece el jefe
     /*const token = sessionStorage.getItem('token'); // Obtén el token del sessionStorage
        const payload = Login.getPayloadFromToken(token);
        const username_user_professor = payload.userProfessor; */

        
//carrera select
const academicPlannigUndegraduateSelect = document.getElementById('academicPlannigUndegraduate');

//Periodicidad
const  periodicitySelect = document.getElementById('periodicity');

//clase
const classSelect = document.getElementById('classPlanning');

//Edificio
const buildingSelect = document.getElementById('building');

//horario
let schedule = document.getElementById('schedule');
let selectedSchedule = '';

//Formulario de creación de secciones
const planningForm = document.getElementById('planningForm');

//Contenedor de centros regionales filtros
const containerFilters = document.getElementById("regionalCenterFilter");



toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);

Sidebar.buildSidebar('../../../')


window.addEventListener('load', async function(event){
    
    event.preventDefault();
    await Schedule.renderSelectSchedules('schedule');
    await AcademicPlanning.regionalCentersAcademicPlanning(idProfessor);
    await RegionalCenter.renderSelectRegionalCentersByProfessor('ademicPlanningCenter',idProfessor);
    
    //Filtro de centro regional
    const selectedFilter= document.querySelector('input[name="btnradio"]:checked');

    //Se obtiene el centro regional
    const idCenter = parseInt(selectedFilter.value, 10);
    
    // Se obtiene las secciones
    const sections = await AcademicPlanning.getClassSectionByDepartmentHeadAcademicPlanning(departmentId, idCenter);
    console.log(idCenter);
    console.log('secciones');
    console.log(sections);


    //Se selecciona el horario que escogió el usuario
    selectedSchedule = schedule.options[schedule.selectedIndex];
    const days = Schedule.getSelectedDays();
    const startTime = selectedSchedule.getAttribute('start_time')
    const endTime = selectedSchedule.getAttribute('end_time')

    //Se cargan los profesores
    Professor.renderSelectProfessors('professor',idCenter, idProfessor,days ,startTime, endTime );
 
})


academicPlanningCenterSelect.addEventListener('change', function(){
    const idCenter = parseInt(academicPlanningCenterSelect.value, 10);
    Career.renderSelectUndergraduatesByCenter('academicPlannigUndegraduate', idProfessor, idCenter);
    Building.renderSelectBuildingsByCenter('building',idProfessor , idCenter);

    //Se selecciona el horario que escogió el usuario
    selectedSchedule = schedule.options[schedule.selectedIndex];
    const days = Schedule.getSelectedDays();
    const startTime = selectedSchedule.getAttribute('start_time')
    const endTime = selectedSchedule.getAttribute('end_time')
    Professor.renderSelectProfessors('professor',idCenter, idProfessor,days ,startTime, endTime );

})

academicPlannigUndegraduateSelect.addEventListener('change', function(){
    const periodicity = parseInt(periodicitySelect.value, 10);
    const career =parseInt(academicPlannigUndegraduateSelect.value, 10);

    Class.renderSelectClassesForPlanning('classPlanning',career , periodicity);
})

periodicitySelect.addEventListener('change', function(){
    const periodicity = parseInt(periodicitySelect.value, 10);
    const career = parseInt(academicPlannigUndegraduateSelect.value, 10);
    Class.renderSelectClassesForPlanning('classPlanning',career , periodicity);
})

classSelect.addEventListener('change', function(){
    const code = parseInt(classSelect.value, 10);
    Class.updateCode('classCode',code );
})

buildingSelect.addEventListener('change', function(){
    const idCenter = parseInt(academicPlanningCenterSelect.value, 10);
    const id_building = parseInt(buildingSelect.value, 10);
    Classroom.renderSelectClassroomsByCenter('classroom', idProfessor,idCenter,id_building);
})


const logoutBtn = document.getElementById('logoutBtn');
logoutBtn.addEventListener('click', function(event){
    event.preventDefault();
    Login.logout('../../../index.html')
});  


/* ========== Evento de creacionde secciones ============*/
planningForm.addEventListener('submit', function(event){
    event.preventDefault();
    const createSectionBtn = document.getElementById('createSectionBtn');

    const formData = new FormData(planningForm);

// Puedes verificar los datos del FormData (opcional)
formData.forEach((value, key) => {
    console.log(key, value);  // Muestra cada par clave-valor de los campos del formulario
});
    Form.checkFormValidity(planningForm.querySelectorAll("input"),createSectionBtn);
    AcademicPlanning.createClassSectionAcademicPlanning(formData);
    console.log('se quiere crear una sección');
})

/* ========== Verificación de campos ============*/
planningForm.addEventListener('change', function(){
    const createSectionBtn = document.getElementById('createSectionBtn');

    Form.checkFormValidity(planningForm.querySelectorAll("input"),createSectionBtn);
   
})

  /* ========== Carga de secciones ============*/

  containerFilters.addEventListener('change',async function(){
    

    //Filtro de centro regional
    const selectedFilter= document.querySelector('input[name="btnradio"]:checked');

    //Se obtiene el centro regional
    const idCenter = parseInt(selectedFilter.value, 10);

    // Se obtiene las secciones
    const sections = await AcademicPlanning.getClassSectionByDepartmentHeadAcademicPlanning(departmentId, idCenter);

    Table.renderDynamicTable(sections,'viewDataSections');


  })

