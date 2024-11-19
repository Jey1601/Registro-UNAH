class Alert{

    static alertPlaceholder = document.getElementById('alertPlaceholder')

    static display(message, type) {
        const wrapper = document.createElement('div')

        wrapper.innerHTML = [
            `<div class="alert alert-${type} alert-dismissible" role="alert">`,
            `   <div>${message}</div>`,
            '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
            '</div>'
          ].join('')


          this.alertPlaceholder.append(wrapper)
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

export{Alert, Modal,Cell , Search};