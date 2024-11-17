import { Cell } from "./support.mjs";
class Applicant {


    static async renderData() {
        console.log('Voy por aquí 1' );
        
        const applications = await this.viewData();


       
            const tableBody = document.querySelector('#viewDataApplicants tbody');
            tableBody.innerHTML='';
           console.log('Voy por aquí');
        
   
           // Comprobamos que tenemos datos antes de intentar renderizarlos
           if (applications && Array.isArray(applications)) {
               var counter = 0;
   
               applications.forEach(application => {
                   
                   counter++;
   
                   const row = document.createElement('tr');
   
                       // Celdas de datos
            const registerNumber = Cell.createCell('th', counter);
            const CellSolicitud = Cell.createCell('td', application.id_admission_application_number);
            const cellName = Cell.createCell('td', application.name);
            const cellLastName = Cell.createCell('td', application.lastname);
            const cellId = Cell.createCell('td', application.id_applicant);
            const cellEmail = Cell.createCell('td', application.email_applicant);
            const cellPhone = Cell.createCell('td', application.phone_number_applicant);
            const cellAddress = Cell.createCell('td', application.address_applicant);
            const cellProcess = Cell.createCell('td', application.name_admission_process);
            const cellCenter = Cell.createCell('td', application.name_regional_center);
            const cellFirst = Cell.createCell('td', application.firstC);
            const cellSecond = Cell.createCell('td', application.secondC); 
            const cellCertificate = Cell.createCell('td', '');
           
            const imgElement = document.createElement('img');
            imgElement.src = application.certificate; // La cadena base64 con el tipo MIME
             
            // Establecer un tamaño adecuado para la imagen (opcional)
            imgElement.style.maxWidth = '100px';  // Limitar el ancho de la imagen a 100px
            imgElement.style.maxHeight = '100px'; // Limitar el alto de la imagen a 100px

            cellCertificate.appendChild(imgElement);
            // Añadir celdas a la fila
            row.appendChild(registerNumber);
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
            row.appendChild(cellCertificate);

            // Añadir la fila a la tabla
            tableBody.appendChild(row);
                   
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
            return [];  // Si hay un error, retornamos un array vac�o
        }
    }
}

export { Applicant };

