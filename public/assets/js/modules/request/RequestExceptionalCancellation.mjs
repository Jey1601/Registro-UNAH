
import { Alert, Cell, Modal } from "../behavior/support.mjs";

class RequestExceptionalCancellation{
    static path = "../../../../";

    static getData(excepcionalCancelationForm, idStudent){
       
        const formData = new FormData(excepcionalCancelationForm);

        const selectedSections = [];
        formData.forEach((value, key) => {
            if (key === 'section') {
              // Convertir cada valor a un entero antes de agregarlo al arreglo
              selectedSections.push(parseInt(value, 10));
            }
          });

        formData.set('idStudent',idStudent)

       // Obtener el archivo 'document' desde formData
            const documentFile = formData.get("document");

            // Crear un Blob con el archivo 'document'
            const documentBlob = new Blob([documentFile], { type: documentFile.type });

            // Reemplazar el archivo 'document' en formData con el Blob
            formData.set("document", documentBlob, documentFile.name);

            // Obtener el archivo 'evidence' desde formData
            const evidenceFile = formData.get("evidence");

            // Crear un Blob con el archivo 'evidence'
            const evidenceBlob = new Blob([evidenceFile], { type: evidenceFile.type });

            // Reemplazar el archivo 'evidence' en formData con el Blob
            formData.set("evidence", evidenceBlob, evidenceFile.name);


         let reason = formData.get('reason') || '';  // Usar valor vacío si no existe
         let justification = formData.get('justification') || '';  // Usar valor vacío si no existe
         
         // Concatenar y convertir a mayúsculas
         formData.append('reasons', (reason + ' ' + justification).toUpperCase());
       
         formData.append('idsClassSections',selectedSections );
      

           //Insertamos la información
          this.makeRequest(formData);
        console.log(formData);
    }


    static confirmTerms(){
        const confirmTerms = document.getElementById('confirmTerms');
        let isChecked = true;
        if (!confirmTerms.checked) {
            Alert.display('info','Debe leer terminos y condiciones','Debe leer y aceptar que leyó los terminos y condiciones', this.path);
            isChecked= false;
        } 

        return isChecked;
    }


    static async makeRequest(formData){
        // Realizar la solicitud
            fetch(this.path+'api/post/student/makeRequestExceptionalCancellationClasses.php', {
                method: 'POST', 
                body: formData
            })
                .then(response => response.json())
                .then(data => {

                
                   Alert.display(data.status,'Aviso',data.message, this.path);
               
                })
                .catch(error => {
                console.error('Error al realizar la solicitud:', error);
                });
    }

  /**
   * Agrega una columna con checkboxes a la tabla que contientes las secciones
   * que tiene actualmente matrículado el estudiante, permitiendo marcar filas, 
   * es decir, las clases que desea cancelar para solicitudes de cancelación.
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-12-09
   * @param {string} tableId - El ID de la tabla a la que se agregarán los checkboxes.
   * @returns {void} No retorna ningún valor.
   */

  static addOptionTableRequestCancellation(tableId) {
    const table = document.getElementById(tableId);
    if (!table) {
      console.error("La tabla no existe.");
      return;
    }

    // Selecciona todas las filas del cuerpo de la tabla
    const rows = table.querySelectorAll("tbody tr");

    rows.forEach((row) => {
      // Crear la celda para el checkbox
      const cellCheckbox = document.createElement("td");

      const cells = row.querySelectorAll("td");
      const sectionId = cells[2].textContent.trim();

      // Crear el contenedor del checkbox
      const divFormCheck = document.createElement("div");
      divFormCheck.classList.add("form-check");
      divFormCheck.style.display = "flex";
      divFormCheck.style.justifyContent = "center";
      // Crear el checkbox
      const checkbox = document.createElement("input");
      checkbox.classList.add("form-check-input");
      checkbox.type = "checkbox";

      //Valor de la sección que se quiere cancelar
      checkbox.name = 'section';
      checkbox.value = sectionId;
      checkbox.id = `check${sectionId}`;

      // Añadir el checkbox al contenedor
      divFormCheck.appendChild(checkbox);

      // Añadir el contenedor a la celda
      cellCheckbox.appendChild(divFormCheck);

      // Añadir la celda a la fila
      row.appendChild(cellCheckbox);
    });
  }        

  static addOptionView(tableId){
    // Selecciona la tabla por su ID
    const table = document.getElementById(tableId);
    if (!table) {
      console.error("La tabla no existe.");
      return;
    }

    // Selecciona todas las filas del cuerpo de la tabla
    const rows = table.querySelectorAll("tbody tr");

    rows.forEach((row) => {
      // Obtén todas las celdas de la fila actual
      const cells = row.querySelectorAll("td");
   
      const idSolicitude = cells[0].textContent.trim();

      //Celda que contendrá las opciones
      const cellOptions = Cell.createCell("td", "");

   
         //Botón de descarga PDF
         const buttonView = document.createElement("button");
         buttonView.classList.add("btn");
     
         buttonView.addEventListener('click',async ()=>{
           //Aquí llamar la función de mostrar la solicitud
            this.getDetailsRequestCancellationExceptional(parseInt(idSolicitude,10));
          
           Modal.showModal('viewInfo');
         })
   
         // Creamos la imagen y configuramos su fuente
         const icon = document.createElement("img");
         icon.src = this.path + "assets/img/icons/zoom-icon.png";

   
         buttonView.appendChild(icon);

         cellOptions.appendChild(buttonView);
     

      row.appendChild(cellOptions); // Agregamos las opciones a la fila
    });
}

static async getDetailsRequestCancellationExceptional(idRequest){

  try {
    const response = await fetch(
      this.path +
        `api/get/professor/getDetailsRequestCancellationExceptional.php?idRequest='${idRequest}'`,
      { 
        method: "GET",  
        headers: {
          "Content-Type": "application/json",
        },
      }
    );

    const responseData = await response.json();
    
  console.log(responseData);
  return responseData;
  } catch (error) {
    console.error("Error:", error);
    return null;
  }    
}


}



export {RequestExceptionalCancellation}