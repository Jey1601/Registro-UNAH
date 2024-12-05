import { Alert, Modal } from "../behavior/support.mjs";
import { Login } from "./login.mjs";

class AcademicPlanning{
    static path = '../../../';

    static async verityAcademicPlanning() {
    
        try {
            const response = await fetch(this.path+"api/get/academicPlanning/verityAcademicPlanning.php");
    
            if (!response.ok) {
                throw new Error("Error en la solicitud: " + response.status);
            }
    
            const data = await response.json();
          
           
           if(data.status == 'success'){
               
            window.location.href = this.path+'views/administration/faculties/academic-planning.html';
             
           }else {
                const body = document.querySelector('#warningModal .modal-body');
                const footer = document.querySelector('#warningModal .modal-footer');
                const warningModalLabel = document.getElementById('warningModalLabel');
                warningModalLabel.innerText = "";
                warningModalLabel.innerText = "Proceso de planificación académica";
                // Limpiar contenido existente
                body.innerHTML = '';
                footer.innerHTML = '';
            
                // Crear el contenedor centralizado
                const centeredContainer = document.createElement('div');
                centeredContainer.className = 'd-flex flex-column justify-content-center align-items-center text-center';
            
                // Crear y agregar imagen con animación
                const imgContainer = document.createElement('div');
                imgContainer.className = 'mb-4';
            
                const img = document.createElement('img');
                img.src = '../../../assets/img/icons/clock-icon.png';
                img.alt = '';
                img.className = 'animated-icon';
                imgContainer.appendChild(img);
            
                // Crear y agregar título
                const title = document.createElement('p');
                title.className = 'fs-4';
                title.textContent = 'El proceso de planificación academica aún no está activo.';
            
             
        
                // Crear y agregar párrafo de información adicional
                const infoParagraph = document.createElement('p');
                infoParagraph.className = 'mt-4';
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
                Modal.showModal('warningModal');
            
            }

        } catch (error) {
            console.log(error);
            Alert.display('error', 'Algo ha salido mal', 'Lo sentimos');
        }
    }



   
    static regionalCentersAcademicPlanning(idProfessor) {
       

        const data = {
            username_user_professor: idProfessor  // Sustituye con el valor que quieras enviar
        };
        fetch( this.path+'api/post/academicPlanning/regionalCentersAcademicPlanning.php', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),  // Convierte el objeto JavaScript a JSON
        })
        .then(response => response.json())  // Convierte la respuesta en formato JSON
        .then(data => {
            console.log("Respuesta del servidor:", data);  // Maneja la respuesta
        })
        .catch((error) => {
            console.error('Error:', error);  // Maneja errores
        });
    
    }
   
}

export {AcademicPlanning};