
import { regular_expressions } from "./configuration.mjs"
class Alert{

    /*static alertPlaceholder = document.getElementById('alertPlaceholder')*/

    /*static display(message, type) {
        const wrapper = document.createElement('div')

        wrapper.innerHTML = [
            `<div class="alert alert-${type} alert-dismissible" role="alert">`,
            `   <div>${message}</div>`,
            '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
            '</div>'
          ].join('')


          this.alertPlaceholder.append(wrapper)
    }*/

          static display(type, title, message) {

            

            // Crear el contenedor de la notificación
            const notification = document.createElement('div');
            notification.classList.add(`notification`);
            notification.classList.add(`${type}`);
            // Crear el icono de la información
            const infoIcon = document.createElement('img');
            infoIcon.src=`assets/img/icons/${type}-icon.png`;
            
            // Crear el contenedor del contenido
            const notificationContent = document.createElement('div');
            notificationContent.classList.add('notification-content');
            
            // Crear el título de la notificación
            const notificationTitle = document.createElement('div');
            notificationTitle.classList.add('notification-title');
            notificationTitle.textContent = title; // Asignar el título
            
            // Crear el texto de la notificación
            const notificationText = document.createElement('span');
            notificationText.textContent = message; // Asignar el texto
            
            // Crear el icono de cerrar
            const closeIcon = document.createElement('img');
            closeIcon.src="assets/img/icons/x-icon.png"
            closeIcon.setAttribute('role', 'button');
            
            // Agregar eventos de cierre
            closeIcon.addEventListener('click', () => {
              notification.remove(); // Eliminar la notificación del DOM
            });
          
            // Construir la notificación
            notificationContent.appendChild(notificationTitle);
            notificationContent.appendChild(notificationText);
            notification.appendChild(infoIcon);
            notification.appendChild(notificationContent);
            notification.appendChild(closeIcon);
          
            // Insertar la notificación en el contenedor de notificaciones
            const notifications = document.getElementById('notifications');
            notifications.appendChild(notification);

            notification.timeOut = setTimeout(
              ()=>notification.remove(), 7000
            )
          }




         
    

}


class Modal{

    static modalInstance = null;

    static showModal(id) {
        const modalElement = document.getElementById(id);
        this.modalInstance = new bootstrap.Modal(modalElement);
        this.modalInstance.show();
      }

      static hideModal() {
        if (this.modalInstance) {
            this.modalInstance.hide();
            this.modalInstance = null; 
        }
      }
}


class Cell{

    // Función para crear celdas de manera reutilizable
  static createCell(type, content) {
    const cell = document.createElement(type);
    cell.textContent = content || '';  // Si no hay contenido, poner un string vacío
    return cell;
  }

}


class Search{
  
  static onInputChange(idInput, idTblBody) {
    //Se escoge el input del cual se toman los datos
    const searchApplication = document.getElementById(idInput);

    //luego agregamos el evento de en cada input buscar
     searchApplication.addEventListener('input', function(){
      let inputText = searchApplication.value.toLowerCase();
      console.log(inputText);

      let tableBody = document.getElementById(idTblBody);
      let tableRows = tableBody.getElementsByTagName('tr');

      for (let i = 0; i < tableRows.length; i++) {
        console.log(tableRows[i].cells[1].textContent);

        let textQueryApplication = tableRows[i].cells[1].textContent.toLowerCase(); //  (primera columna con datos)
        let textQueryName = tableRows[i].cells[2].textContent.toLowerCase(); // (Segunda columna con datos)
        let textQueryLastName = tableRows[i].cells[3].textContent.toLowerCase(); // (Tercera columna con datos)

        // Buscamos por el número de solicitud o por nombre
        if (textQueryApplication.indexOf(inputText) === -1 && textQueryName.indexOf(inputText) === -1 && textQueryLastName.indexOf(inputText) === -1) {
            tableRows[i].style.display = "none"; // Ocultar fila
        } else {
            tableRows[i].style.display = ""; // Mostrar fila
        }
    }

     } );


    
}
}

class Entry{

  static createEntry(subtitleText, textContent) {
    const entry = document.createElement("div");
    const subtitle = document.createElement("h5");
    const text = document.createElement("p");

    subtitle.innerText = subtitleText;
    text.innerHTML = textContent;

    entry.appendChild(subtitle);
    entry.appendChild(text);

    return entry;
}




}

class Form{

  static validateInput(event){
     switch (event.target.name){
        case 'applicantName':
          this.validateField(regular_expressions.name,event.target);
        break;

        case 'applicantLastName':
          this.validateField(regular_expressions.LastName,event.target);
        break;
        
        case 'applicantIdentification':
          this.validateField(regular_expressions.idNum,event.target);
        break;

        case 'applicantPhoneNumber':
          this.validateField(regular_expressions.phone,event.target);
        break;
       
        case 'applicantEmail':
          this.validateField(regular_expressions.email,event.target);
        break;

        case 'applicantDirection':
          this.validateField(regular_expressions.address,event.target);
        break;
   

     }
  }


  static  validateField(expression, input) {

    const errorElement = input.nextElementSibling; // Obtén el <p> inmediatamente después del input

    if(expression.test(input.value)){
     input.classList.add('right-input');
     input.classList.remove('wrong-input');
     errorElement.classList.remove('input-error-active');
     
    }else{
     input.classList.add('wrong-input');
     input.classList.remove('right-input');
     errorElement.classList.add('input-error-active');
     
    }

  }


  static checkFormValidity(inputsForm, submitButton)  {
    let isFormValid = true;
    
    
    // Recorre todos los inputs del formulario
    inputsForm.forEach(input => {
      

      // Verificación para los archivos
      if(input.type === 'file'){
        if(input.files.length === 0){
          isFormValid = false;
        
        }
  
      // Verificación para los selects
      } else if(input.tagName === 'SELECT'){
        if(!input.value){
          isFormValid = false;
        
        }
  
      // Verificación para otros inputs
      } else {
        if (input.classList.contains('wrong-input')) {
          isFormValid = false;
        
        }
      }
    });
  
    // Aquí se maneja el estado del botón al final de todas las verificaciones
    if (isFormValid) {
      submitButton.classList.add('oficial-blue');
      submitButton.classList.remove('wrong-form');
      submitButton.innerText = "Enviar";
      submitButton.disabled = false;  // Habilita el botón
 
    } else {
      submitButton.classList.remove('oficial-blue');
      submitButton.classList.add('wrong-form');
      submitButton.innerText = "¡Verifica tus datos!";
      submitButton.disabled = true;  // Deshabilita el botón

    }
  
    return isFormValid;
  }

}

export{Alert, Modal,Cell , Search, Entry, Form};