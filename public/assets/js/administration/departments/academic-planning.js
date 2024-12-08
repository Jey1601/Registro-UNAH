import { Sidebar } from "../../modules/behavior/support.mjs";
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
 //Aquí se debe obtener el usuario del profesor del token

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


toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);

Sidebar.buildSidebar('../../../')


window.addEventListener('load', function(event){
    
    event.preventDefault();
    Schedule.renderSelectSchedules('schedule');
    AcademicPlanning.regionalCentersAcademicPlanning(idProfessor);
    RegionalCenter.renderSelectRegionalCentersByProfessor('ademicPlanningCenter',idProfessor);
    
    
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
    console.log(days);
    console.log(startTime);
    console.log(endTime);
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
