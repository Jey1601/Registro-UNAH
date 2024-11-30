
import { regular_expressions } from "./configuration.mjs"
class Alert{

   

          static display(type, title, message, path = '') {

            

            // Crear el contenedor de la notificación
            const notification = document.createElement('div');
            notification.classList.add(`notification`);
            notification.classList.add(`${type}`);
            // Crear el icono de la información
            const infoIcon = document.createElement('img');
            infoIcon.src=path.concat(`assets/img/icons/${type}-icon.png`);
            
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
            closeIcon.src=path.concat("assets/img/icons/x-icon.png")
            closeIcon.setAttribute('role', 'button');
            
            // Agregar eventos de cierre
            closeIcon.addEventListener('click', () => {
              // Añadir la clase para la animación de salida
              notification.classList.add('hide');
          
              
              setTimeout(() => {
                  notification.remove(); // Eliminar la notificación del DOM
              }, 300); // 
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
            
            notification.timeOut = setTimeout(() => {
              if (notification) {
                  // Añade una clase para la animación de salida
                  notification.classList.add('hide');
                  
                  // Espera a que termine la animación antes de eliminar el elemento
                  setTimeout(() => notification.remove(), 300); // Ajusta según la duración de la animación "hide"
              }
          }, 7000);
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
      
      if(input.disabled == 'false' || input.type != 'hidden'){
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


  static changeActionByChecks(idForm,idButton,messageTrue,messageFalse, actionTrue, actionFalse){
     const submitButton = document.getElementById(idButton);

     // Crear un objeto para almacenar los checkboxes seleccionados
     var selectedCheckboxes = [];

     // Iterar sobre todos los checkboxes seleccionados y agregar sus valores a selectedCheckboxes
     document.querySelectorAll('input[type="checkbox"]:checked').forEach(function(checkbox) {
       selectedCheckboxes.push(checkbox.value);
     });

     if(selectedCheckboxes.length>0){
      submitButton.classList.remove('oficial-blue');
      submitButton.classList.add('wrong-form');
      submitButton.innerText = messageFalse;
      submitButton.setAttribute('data-action','Deny')

    

     }else{
      submitButton.classList.add('oficial-blue');
      submitButton.classList.remove('wrong-form');
      submitButton.innerText = messageTrue;
      submitButton.setAttribute('data-action','Approve');
   
     }
  }
 

}



export{Alert, Modal,Cell , Search, Entry, Form};