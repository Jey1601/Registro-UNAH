import { Form, File, Sidebar } from "../../modules/behavior/support.mjs";
import { Professor } from "../../modules/request/AdminFaculties.mjs";
/* ========== Constantes del DOM ============*/
const toggleSidebarButton = document.getElementById("toggleSidebar");

const closeSidebarButton = document.getElementById("closeSidebar");

//Obtener el formulario de creación docente
const professorCreationForm = document.getElementById('professorCreationForm');

//Obtener inputs del formulario de creación docente
const inputsProfessorCreationForm = Array.from(professorCreationForm.elements);

//Obtener el botón de creación del formulario de creación docente
const submitButton = document.getElementById('createProfessorButton');

/* ========== Creando y dando funcionalidad al sidebar ============*/

//Consruir la slidebar en base a permisos

Sidebar.buildSidebar('../../../');

//Funcionalidad del sidebar

toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);


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