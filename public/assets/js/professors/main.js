import { Sidebar, Search, Modal, Form } from "../modules/behavior/support.mjs";
import { Login } from "../modules/request/login.mjs";
import { Table } from "../modules/behavior/support.mjs";
import { Section } from "../modules/request/Section.mjs";
import { Professor } from "../modules/request/Professor.mjs";
const toggleSidebarButton = document.getElementById("toggleSidebar");
const closeSidebarButton = document.getElementById("closeSidebar");

const path = "../../";

toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);

Sidebar.buildSidebar("../../");
//Formulario para actualizar el video

const videoForm = document.getElementById('videoForm');
//Botón de guardado de url 
const saveUrlButton = document.getElementById('saveUrlButton');
const logoutBtn = document.getElementById("logoutBtn");
logoutBtn.addEventListener("click", function (event) {
  event.preventDefault();
  Login.logout(path + "/index.html");
});


window.addEventListener("load", async function () {
  const classes = await  Professor.getAssignedClasses(1);

  Table.renderDynamicTable(classes, "viewSections");
  Search.onInputChange("searchSection", "viewDataSectionsBody");
  Section.addOptions("viewSections");
  //Agregamos el evento a los botones para poder agregar el video
    const addVideoButtons = document.querySelectorAll(".btn-video");

    

    addVideoButtons.forEach((button) => {
    button.addEventListener("click", function () {
   

        document.getElementById('sectionField').value =button.getAttribute('section') ;
     
        Modal.showModal("videoModal");

      


    });
    });

    //Agregamos el evento a los botones para poder descargar listado de estudiantes
    const downloadButtons = document.querySelectorAll(".btn-download");

    downloadButtons.forEach((button) => {
    button.addEventListener("click", function () {
        //Aquí se debe llamar al endpoint que descarga la info de la sección
        console.log(button.getAttribute('section'));
        
    });
    });


     //Agregamos el evento a los botones para poder calificar estudiantes
     const gradeButtons = document.querySelectorAll(".btn-grade");

     gradeButtons.forEach((button) => {
     button.addEventListener("click", function () {

        //Aquí se debe verificar que el proceso de subir calificaciones este activo.

        //Si está activo se abre la modal de calificaciones o la pagina de calificaciones

     
         console.log(button.getAttribute('section'));
         
     });
     });


});

videoForm.addEventListener('change', function(){
  const urlVideo = document.getElementById('urlVideo');
  const inputs = videoForm.querySelectorAll('input');

  urlVideo.addEventListener('blur', function(event){
    Form.validateInput(event);
    Form.checkFormValidity(inputs,saveUrlButton)
  });
  
 
  Form.checkFormValidity(inputs,saveUrlButton)
})



videoForm.addEventListener('submit', async function(event){
  const section = document.getElementById('sectionField').value;
  const urlVideo = document.getElementById('urlVideo').value;
  event.preventDefault();
  saveUrlButton.disabled = true;
  //El id del profesor se debe obtener dinamicamente del payload
  const status = await Professor.setUrlVideoClassSection(1,urlVideo,section);
  saveUrlButton.disabled = false;

  if(status == 'success'){
   Modal.hideModal('videoModal') 
  }
})




