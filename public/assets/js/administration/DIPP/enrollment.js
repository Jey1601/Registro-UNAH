import { EnrollmentProcess } from "../../modules/request/EnrollmentProcess.mjs";
import { Sidebar } from "../../modules/behavior/support.mjs";
/* ========== Constantes  ============*/
const toggleSidebarButton = document.getElementById("toggleSidebar");
const closeSidebarButton = document.getElementById("closeSidebar");


/* ========== Funcionalidad del sidebar  ============*/
toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);

/* ========== Construcción del sidebar  ============*/
Sidebar.buildSidebar('../../../../')


window.addEventListener('load', async  function(){
   const response = await EnrollmentProcess.verifyEnrollmentProcessStatus();

  

    if (response.status != "success") {
        window.location.href = 
          "../../../../views/administration/DIPP/upload-students.html";
      } 

    const data =  await EnrollmentProcess.verifyDatesEnrollmentProcess(); 
    

    const tbody = document.getElementById("calendar-body");

    data.data.forEach(entry => {
      const row = document.createElement("tr");

      const dateCell = document.createElement("td");
      dateCell.textContent = entry.day_available_enrollment_process;
      row.appendChild(dateCell);

      const timeCell = document.createElement("td");
      timeCell.textContent = `${entry.start_time_available_enrollment_process} - ${entry.end_time_available_enrollment_process}`;
      row.appendChild(timeCell);

      const studentCell = document.createElement("td");
      const messageList = document.createElement("ul");

      entry.message.split(",").forEach(message => {
        if (message.trim() !== "") {
          const listItem = document.createElement("li");
          listItem.textContent = message.trim();
          messageList.appendChild(listItem);
        }
      });

      studentCell.appendChild(messageList);
      row.appendChild(studentCell);

      tbody.appendChild(row);
    });


})


