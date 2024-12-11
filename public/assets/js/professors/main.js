import { Sidebar, Search, Modal, Form, Alert } from "../modules/behavior/support.mjs";
import { Login } from "../modules/request/login.mjs";
import { Table } from "../modules/behavior/support.mjs";
import { Section } from "../modules/request/Section.mjs";
import { Professor } from "../modules/request/Professor.mjs";

const path = "../../";


//Variable que almacena el usuariol professor
let idProfessor = ''


  /* ========== Funciones que se deben cargar junto con la página ============*/
window.addEventListener("load", async function () {
  //Obtenemos la ifnromación del token
  const token = sessionStorage.getItem('token'); // Obtén el token del sessionStorage

  if (!token)  // Si no hay token, no se ejecuta lo demás
  
  this.window.location.href ='../../../index.html'
 try {
   
   const payload = Login.getPayloadFromToken(token);
   idProfessor = payload.username;
  
 } catch (error) {
   // Si ocurre un error, simplemente no se ejecuta el resto del código.
   console.log(error);
   this.window.location.href ='../../../index.html'
 }


  const classes = await Professor.getAssignedClasses(idProfessor);

  if(classes != null){
    Table.renderDynamicTable(classes, "viewSections");
    Search.onInputChange("searchSection", "viewDataSectionsBody");
    Section.addOptions("viewSections");
  }else{
    Alert.display('info','Nada que mostrar','No tiene clases asignadas aún','../../../')
  }
  
  //Agregamos el evento a los botones para poder agregar el video
  const addVideoButtons = document.querySelectorAll(".btn-video");

  addVideoButtons.forEach((button) => {
    button.addEventListener("click", function () {
      document.getElementById("sectionField").value =
        button.getAttribute("section");

      Modal.showModal("videoModal");
    });
  });

  /* ========== Funcionalidad de sidebar ============*/
  const toggleSidebarButton = document.getElementById("toggleSidebar");
  const closeSidebarButton = document.getElementById("closeSidebar");

  toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
  closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);

  Sidebar.buildSidebar("../../");

  //Formulario para actualizar el video

  const videoForm = document.getElementById("videoForm");

  //Botón de guardado de url
  const saveUrlButton = document.getElementById("saveUrlButton");
  const logoutBtn = document.getElementById("logoutBtn");
  logoutBtn.addEventListener("click", function (event) {
    event.preventDefault();
    Login.logout(path + "/index.html");
  });

  //Agregamos el evento a los botones para poder descargar listado de estudiantes
  const downloadButtons = document.querySelectorAll(".btn-download");

  downloadButtons.forEach((button) => {
    button.addEventListener("click", function () {
      //Aquí se debe llamar al endpoint que descarga la info de la sección
      console.log(button.getAttribute("section"));
    });
  });

  //Agregamos el evento a los botones para poder calificar estudiantes
  const gradeButtons = document.querySelectorAll(".btn-grade");

  gradeButtons.forEach((button) => {
    button.addEventListener("click", function () {
      //Aquí se debe verificar que el proceso de subir calificaciones este activo.

      //Si está activo se abre la modal de calificaciones o la pagina de calificaciones

      console.log(button.getAttribute("section"));
    });
  });
});

videoForm.addEventListener("change", function () {
  const urlVideo = document.getElementById("urlVideo");
  const inputs = videoForm.querySelectorAll("input");

  urlVideo.addEventListener("blur", function (event) {
    Form.validateInput(event);
    Form.checkFormValidity(inputs, saveUrlButton);
  });

  Form.checkFormValidity(inputs, saveUrlButton);
});

videoForm.addEventListener("submit", async function (event) {
  const section = document.getElementById("sectionField").value;
  const urlVideo = document.getElementById("urlVideo").value;
  event.preventDefault();
  saveUrlButton.disabled = true;
  //El id del profesor se debe obtener dinamicamente del payload
  const status = await Professor.setUrlVideoClassSection(1, urlVideo, section);
  saveUrlButton.disabled = false;

  if (status == "success") {
    Modal.hideModal("videoModal");
  }
});
