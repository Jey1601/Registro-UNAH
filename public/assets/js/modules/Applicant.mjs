class Applicant {


    static async renderData() {
       
        applicantStudyCenter.innerHTML= '';
      
         const tableBody = document.querySelector('#viewDataApplicants tbody');
         tableBody.innerHTML='';

        const applications = await this.viewData();

        // Comprobamos que tenemos datos antes de intentar renderizarlos
        if (applications && Array.isArray(applications)) {
            var counter = 0;

            applications.forEach(application => {
                
                counter++;

                const row = document.createElement('tr');

                const registerNumber = document.createElement('th')
                registerNumber.textContent = counter;

                // Crear las celdas para cada columna   
                const CellSolicitud = document.createElement('td');
                CellSolicitud.textContent = application.id_admission_application_number;
                
                const cellName = document.createElement('td');
                cellName.textContent = application.name;
                
                const cellLastName = document.createElement('td');
                cellLastName.textContent = application.lastname;
                
                const cellId = document.createElement('td');
                cellId.textContent = application.id_applicant;

                const cellEmail = document.createElement('td');
                cellEmail.textContent = application.email_applicant;
                
                const cellPhone = document.createElement('td');
                cellPhone.textContent = application.phone_number_applicant;

                const cellAddress = document.createElement('td');
                cellAddress.textContent = application.address_applicant;

                const cellProcess = document.createElement('td');
                cellProcess.textContent = application.name_admission_process;

                const cellCenter = document.createElement('td');
                cellCenter.textContent = application.name_regional_center;

                const cellFirst = document.createElement('td');
                cellFirst.textContent = application.name_undergraduate;

                const cellSecond = document.createElement('td');
                cellSecond.textContent = application.name_undergraduate;
                
               // Agregar cada celda a la fila
                row.appendChild(CellSolicitud);
                row.appendChild(cellName);
                row.appendChild(cellLastName);
                row.appendChild(cellId);
                row.appendChild(cellEmail);
                row.appendChild(cellPhone);
                row.appendChild(cellAddress);
                row.appendChild(cellProcess);
                row.appendChild(cellCenter);
                row.appendChild(cellFirst);
                row.appendChild(cellSecond);

                
                // Añadir la fila al cuerpo de la tabla
                tableBody.appendChild(row);
                    
            });
            
            window.location.href = ".../../../views/administration/see-inscription.html";
        } else {
            console.error("No se encontraron solicitudes de apliación");
        }
    }

    // Método para obtener los datos de las solicitudes de aplicación
    static async viewData() {
        try {
            const response = await fetch("../../../api/get/applicant/viewData.php");

            if (!response.ok) {
                throw new Error("Error en la solicitud: " + response.status);
            }

            const data = await response.json();
            console.log("Datos recibidos:", data);
            return data; 
        } catch (error) {
            console.error("Hubo un error:", error);
            return [];  // Si hay un error, retornamos un array vacío
        }
    }
}

export { Applicant };