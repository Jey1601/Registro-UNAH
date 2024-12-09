import { Sidebar, Search, Modal } from "../modules/behavior/support.mjs";
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

const logoutBtn = document.getElementById("logoutBtn");
logoutBtn.addEventListener("click", function (event) {
  event.preventDefault();
  Login.logout(path + "/index.html");
});

const classData = [
  {
    codigo: "MAT101",
    clase: "Matemáticas Básicas",
    seccion: "A",
    dias: "Lunes y Miércoles",
    hi: "08:00 AM",
    hf: "10:00 AM",
    aula: "Aula 101",
    cupos: 25,
  },
  {
    codigo: "QUI102",
    clase: "Química General",
    seccion: "B",
    dias: "Martes y Jueves",
    hi: "10:00 AM",
    hf: "12:00 PM",
    aula: "Laboratorio 3",
    cupos: 15,
  },
  {
    codigo: "HIS201",
    clase: "Historia Universal",
    seccion: "C",
    dias: "Viernes",
    hi: "01:00 PM",
    hf: "03:00 PM",

    aula: "Aula 202",
    cupos: 30,
  },
  {
    codigo: "ING303",
    clase: "Inglés Avanzado",
    seccion: "D",
    dias: "Lunes, Miércoles y Viernes",
    hi: "02:00 PM",
    hf: "04:00 PM",

    aula: "Aula 304",
    cupos: 20,
  },
  {
    codigo: "FIS301",
    clase: "Física Aplicada",
    seccion: "E",
    dias: "Martes y Jueves",
    hi: "08:00 AM",
    hf: "10:00 AM",
    aula: "Laboratorio 5",
    cupos: 10,
  },
];

window.addEventListener("load", function () {
  Table.renderDynamicTable(classData, "viewSections");
  Search.onInputChange("searchSection", "viewDataSectionsBody");
  Section.addOptions("viewSections");

  //Agregamos el evento a los botones para poder agregar el video
    const addVideoButtons = document.querySelectorAll(".btn-video");

     Professor.getAssignedClasses(1);

    addVideoButtons.forEach((button) => {
    button.addEventListener("click", function () {
        //Aquí se debe llamar la modal para agregar video
        console.log(button.getAttribute('section'));
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


