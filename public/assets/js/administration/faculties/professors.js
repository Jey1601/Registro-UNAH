import { Form, File, Sidebar, Alert, Table } from "../../modules/behavior/support.mjs";
import { Professor } from "../../modules/request/AdminFaculties.mjs";
import { RegionalCenter } from "../../modules/request/RegionalCenter.mjs";
import { Department } from "../../modules/request/Departments.mjs";
import { Login } from "../../modules/request/login.mjs";
/* ========== Constantes del DOM ============*/
const toggleSidebarButton = document.getElementById("toggleSidebar");

const closeSidebarButton = document.getElementById("closeSidebar");

//Obtener el formulario de creación docente
const professorCreationForm = document.getElementById('professorCreationForm');

//Obtener inputs del formulario de creación docente
const inputsProfessorCreationForm = Array.from(professorCreationForm.elements);

//Obtener el select de centros reginales
const professorDepartment = document.getElementById('professorDepartment');

//Obtener el botón de creación del formulario de creación docente
const submitButton = document.getElementById('createProfessorButton');


/* ========== Creando y dando funcionalidad al sidebar ============*/

//Consruir la slidebar en base a permisos

Sidebar.buildSidebar('../../../');

//Funcionalidad del sidebar

toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);

/* ========== Rellenando select  y tabla de profesores============*/
window.addEventListener('load', async () => {
  RegionalCenter.renderSelectRegionalCentersByDepartment('professorCenter','professorDepartment');
  Department.renderSelectDepartmentsByFaculty('professorDepartment', 1);
  
  
  const data = await Professor.getProfessorsByFaculty(1);
  if(!'professors' in data){
    Alert.display('warning','oh no', 'Al parecer no hay profesores asignados a esta facultad', '../../../../')
    Table.renderDynamicTable(data.professors,'viewDataProfessors');
  }else{
    console.log('aquí');
    Table.renderDynamicTable(data.professors,'viewDataProfessors');
    Professor.addOptions('viewDataProfessors');
  }
});

professorDepartment.addEventListener('change', function(){
  RegionalCenter.renderSelectRegionalCentersByDepartment('professorCenter','professorDepartment');
})
/* ========== Validando el formato de los input ============*/

//Verificar los inputs del formulario
inputsProfessorCreationForm.forEach((input) => {


    
  input.addEventListener('blur', function(event){
    Form.validateInput(event, submitButton);
    Form.checkFormValidity(inputsProfessorCreationForm,submitButton);

  });
 
  
});

//Verificar el file del formulario
document.getElementById('professorPicture').addEventListener('change', function(event) {
    const fileInput = event.target;  // Referencia al input
    const file = fileInput.files[0]; // Obtener el archivo seleccionado
    
    File.validateFile(file, fileInput, '../../../');
  
  });
 
/* ========== Obteniendo la información del formulario ============*/  
submitButton.addEventListener('click', function(event){
    console.log('aquí');
    event.preventDefault();
    Professor.getData();
});

const logoutBtn = document.getElementById('logoutBtn');
logoutBtn.addEventListener('click', function(event){
    event.preventDefault();
    Login.logout('../../../index.html')
});  



