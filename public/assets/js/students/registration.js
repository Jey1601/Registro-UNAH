import { Sidebar, Table, Modal } from "../modules/behavior/support.mjs";
import { EnrollmentProcess } from "../modules/request/EnrollmentProcess.mjs";
import { Login } from "../modules/request/login.mjs";


   /**
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-11
   */

/* ========== Constantes  ============*/
const toggleSidebarButton = document.getElementById("toggleSidebar");
const closeSidebarButton = document.getElementById("closeSidebar");
let idStudent = "";


const departmentSelect = document.getElementById("departmentSelect");
const classSelect = document.getElementById("classSelect");
const enrollmentButton = document.getElementById('enrollmentButton');
const sectionForm = document.getElementById('sectionForm');
let sectionToEnroll = null; 
toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);

Sidebar.buildSidebar("../../../");

window.addEventListener("load", async function () {

 const token = sessionStorage.getItem('token'); // Obtén el token del sessionStorage

  try {
    
    const payload = Login.getPayloadFromToken(token);
    idStudent = payload.username;
    console.log(payload);
  } catch (error) {
    // Si ocurre un error, simplemente no se ejecuta el resto del código.
    console.log(error);
    //this.window.location.href ='../../../index.html'
  }

    const response = await EnrollmentProcess.verifyEnrollmentProcessStatus();
    
  
    //Si el proceso no esta activo se muestra eso
    if (response.status != "success") {
        window.location.href = 
          "../../../views/students/index.html";
    }else{
        const res =  await EnrollmentProcess.verifyStatusEnrollmentProcessStudent(idStudent);

        if(res.status != 'success'){

            const contentSection = this.document.getElementById('contentSection');
            contentSection.diabled = true;

            const body = document.querySelector("#warningModal .modal-body");
            const footer = document.querySelector("#warningModal .modal-footer");
            const warningModalLabel = document.getElementById("warningModalLabel");
            warningModalLabel.innerText = "";
            warningModalLabel.innerText = "Fecha de matrícula";
            // Limpiar contenido existente
            body.innerHTML = "";
            footer.innerHTML = "";
        
            // Crear el contenedor centralizado
            const centeredContainer = document.createElement("div");
            centeredContainer.className =
            "d-flex flex-column justify-content-center align-items-center text-center";
        
            // Crear y agregar imagen con animación
            const imgContainer = document.createElement("div");
            imgContainer.className = "mb-4";
        
            const img = document.createElement("img");
            img.src = "../../../../assets/img/icons/clock-icon.png";
            img.alt = "";
            img.className = "animated-icon";
            imgContainer.appendChild(img);
        
            // Crear y agregar título
            const title = document.createElement("p");
            title.className = "fs-4";
            title.textContent =
            res.message;
        
            // Crear y agregar párrafo de información adicional
            const infoParagraph = document.createElement("p");
            infoParagraph.className = "mt-4";
            infoParagraph.innerHTML = `
                        Revisa las fechas del proceso en.
                        <a href="https://www.unah.edu.hn/calendarios" class="text-decoration-none text-primary fw-bold">
                            Calendarios
                        </a> 
                    `;
        
            // Agregar todos los elementos al contenedor centralizado
            centeredContainer.appendChild(imgContainer);
            centeredContainer.appendChild(title);
            centeredContainer.appendChild(infoParagraph);
        
            // Agregar el contenedor al cuerpo del modal
            body.appendChild(centeredContainer);
        
            // Mostrar la modal
            Modal.showModal("warningModal");

            this.setTimeout(()=>{
               this.window.location.href ="../../../views/students/index.html"
            },7000 );

            }

           

    }


    enrollmentButton.disabled= true;
    enrollmentButton.innerText = 'Selecciona una sección';

  const data = await EnrollmentProcess.getPendingClassesStudent(idStudent);
  EnrollmentProcess.populateDepartments(data, departmentSelect);

  // Evento para cambiar las clases cuando se selecciona un departamento
  departmentSelect.addEventListener("change", () => {
    const selectedDepartment = departmentSelect.value;
    EnrollmentProcess.populateClasses(data, selectedDepartment, classSelect);
  });

  classSelect.addEventListener('change', async ()=>{
    const selectedClass = parseInt(classSelect.value,10);
    const classes = await EnrollmentProcess.getClassSectionsForStudent(idStudent,selectedClass);

    Table.renderDynamicTable(classes, 'viewSections');
    EnrollmentProcess.addOptionsSectionEnrollment('viewSections');
  });

  enrollmentButton.addEventListener('click', function(event){
    sectionToEnroll = document.querySelector('input[name="sectionToEnroll"]:checked').value;
    event.preventDefault();

    EnrollmentProcess.insertEnrollmentClassSection(idStudent,parseInt(sectionToEnroll,10));
})

});

sectionForm.addEventListener('change', function(){
    sectionToEnroll = document.querySelector('input[name="sectionToEnroll"]:checked')
    
    if(sectionToEnroll!=null){
        enrollmentButton.disabled = false;
         enrollmentButton.innerText = 'Matrícular';
    }else{
        enrollmentButton.disabled= true;
        enrollmentButton.innerText = 'Selecciona una sección';
    }
    
})

