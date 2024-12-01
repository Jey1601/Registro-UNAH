import { Alert, Modal } from "../behavior/support.mjs";
import { RegionalCenter } from "./RegionalCenter.mjs";

class AdmissionProccess{
    static path = '../../../';

    static async getCurrentProccess() {
    
        try {
            const response = await fetch(this.path+"api/get/admisionProcess/currentAdmissionProccess.php");
    
            if (!response.ok) {
                throw new Error("Error en la solicitud: " + response.status);
            }
    
            const data = await response.json();
     
           
           if(data.status == 'success'){
               
                RegionalCenter.renderSelectRegionalCenters();
                Modal.showModal('Inscription-form');
           }else{

                Alert.display(data.status,'Aviso', data.message);
           }

        } catch (error) {
            console.log(error);
            Alert.display('error', 'Algo ha salido mal', 'Lo sentimos');
        }
    }

    static async verifyAdmissionProcess() {
    
        try {
            const response = await fetch(this.path+"api/get/admisionProcess/VerifyAdmissionProcess.php");
    
            if (!response.ok) {
                throw new Error("Error en la solicitud: " + response.status);
            }
    
            const data = await response.json();
     
           
           if(data.status == 'success'){
               
                RegionalCenter.renderSelectRegionalCenters();
                Modal.showModal('Inscription-form');
           }else{
                
                const body = document.querySelector('#Inscription-form .modal-body');
                const footer = document.querySelector('#Inscription-form .modal-footer');

               // body.innerHTML = '';
               // footer.innerHTML = ''; 
                Modal.showModal('Inscription-form');
                Alert.display(data.status,'Aviso', data.message);
           }

        } catch (error) {
            console.log(error);
            Alert.display('error', 'Algo ha salido mal', 'Lo sentimos');
        }
    }


    static async verifyInscriptionAdmissionProcess() {
    
        try {
            const response = await fetch(this.path+"api/get/admisionProcess/VerifyInscriptionAdmissionProcess.php");
    
            if (!response.ok) {
                throw new Error("Error en la solicitud: " + response.status);
            }
    
            const data = await response.json();
          
           
           if(data.status == 'success'){
               
                RegionalCenter.renderSelectRegionalCenters();
                Modal.showModal('Inscription-form');
           }else {
                const body = document.querySelector('#Inscription-form .modal-body');
                const footer = document.querySelector('#Inscription-form .modal-footer');
            
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
                img.src = './assets/img/icons/clock-icon.png';
                img.alt = '';
                img.className = 'animated-icon';
                imgContainer.appendChild(img);
            
                // Crear y agregar título
                const title = document.createElement('p');
                title.className = 'fs-4';
                title.textContent = 'El próximo proceso de admisión inicia el:';
            
                // Crear y agregar fecha destacada
                const highlight = document.createElement('div');
                highlight.className = 'highlight mx-auto';
                highlight.textContent = '15 de enero de 2025';
            
                // Crear y agregar párrafo de información adicional
                const infoParagraph = document.createElement('p');
                infoParagraph.className = 'mt-4';
                infoParagraph.innerHTML = `
                    Prepárate para esta fecha revisando los 
                    <a href="https://admisiones.unah.edu.hn/" class="text-decoration-none text-primary fw-bold">
                        requisitos
                    </a> 
                    y asegurándote de completar todos los documentos necesarios. ¡Estamos emocionados de verte formar parte de nuestra comunidad universitaria!
                `;
            
                // Agregar todos los elementos al contenedor centralizado
                centeredContainer.appendChild(imgContainer);
                centeredContainer.appendChild(title);
                centeredContainer.appendChild(highlight);
                centeredContainer.appendChild(infoParagraph);
            
                // Agregar el contenedor al cuerpo del modal
                body.appendChild(centeredContainer);
            
                // Mostrar la modal
                Modal.showModal('Inscription-form');
            
                // Mostrar alerta
                Alert.display(data.status, 'Aviso', data.message);
            }

        } catch (error) {
            console.log(error);
            Alert.display('error', 'Algo ha salido mal', 'Lo sentimos');
        }
    }


}


export {AdmissionProccess};


