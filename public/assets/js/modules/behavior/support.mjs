
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

    if(input.name == 'applicantIdentification' ){
      // Obtener el año actual
      const currentYear = new Date().getFullYear();

      // Calcular el año mínimo y máximo de nacimiento
      const minYear = currentYear - 90; // 90 años atrás
      const maxYear = currentYear - 10; // 10 años atrás

      let cadena = input.value;
      let subcadena = cadena.slice(4, 8); // Extrae del 5º al 8º dígito (índices 4 a 7)
      let year = parseInt(subcadena); // Convierte la subcadena a entero

      if(year>=minYear && year<=maxYear){
     
        input.classList.add('right-input');
        input.classList.remove('wrong-input');
        errorElement.classList.remove('input-error-active');
      }else{
        input.classList.add('wrong-input');
        input.classList.remove('right-input');
        errorElement.classList.add('input-error-active');
      
      }
   
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


class File {
  static validateFile(file,input, path) {
    const maxSize = 5 * 1024 * 1024;  // 5 MB en bytes (tamaño máximo)
    const minSize = 100 * 1024;  // 100 KB en bytes (tamaño mínimo)

    // Verificar el tamaño del archivo
    if (file.size > maxSize) {
      input.value = "";
      Alert.display('error','Archivo incorrecto','El archivo es más grande de lo esperado.', path);
      return Promise.resolve(false);  // Si es demasiado grande, retornamos false
    } else if (file.size < minSize) {
      input.value = "";
      Alert.display('error','Archivo incorrecto','El archivo es más pequeño de lo esperado.', path);
      return Promise.resolve(false);  // Si es demasiado pequeño, retornamos false
    }

    // Verificar tipo de archivo
    const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    const validPdfTypes = ['application/pdf'];

    if (!validImageTypes.includes(file.type) && !validPdfTypes.includes(file.type)) {
        input.value = "";
        Alert.display('error','Archivo incorrecto','Sube un archivo con el formato requerido', path);
       return Promise.resolve(false);  // Si el tipo de archivo no es válido, retornamos false
    }

    // Si es PDF, no es necesario verificar dimensiones
    if (validPdfTypes.includes(file.type)) {

       return Promise.resolve(true);
    } 

    // Verificar las dimensiones del archivo si es una imagen
    const reader = new FileReader();

    return new Promise((resolve) => {
        reader.onload = function(event) {
            const img = new Image();

            img.onload = function() {
                const width = img.width;
                const height = img.height;

                // Verificar las dimensiones mínimas de la imagen
                if (width < 800 || height < 1200) {
                   input.value = "";
                   Alert.display('error','Archivo incorrecto','La imagen no tiene la resolución requerida');
                   resolve(false);  // Si la imagen es demasiado pequeña, retornamos false
                } else {
                   resolve(true);  // Si la imagen cumple con los requisitos, retornamos true
                }
            };

            img.src = event.target.result;  // Cargar la imagen en el objeto Image
        };

        // Leer el archivo como URL de datos (esto activa el evento onload)
        reader.readAsDataURL(file);
    });
}

}



export{Alert, Modal,Cell , Search, Entry, Form, File};