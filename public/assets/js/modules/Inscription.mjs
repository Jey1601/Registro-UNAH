import { regular_expressions } from "./configuration.mjs";

class Inscription{

  static modalInstance = null;

  static async  getData(){
      const inscriptionForm = document.getElementById('inscriptionForm');
        // Crear un nuevo objeto FormData
        const formData = new FormData(inscriptionForm);


       
      // Obtener los valores de los campos del formulario
      const applicant_name = formData.get('applicantName');
      const applicant_last_name = formData.get('applicantLastName');

      const file = formData.get('aplicantCertificate');

      const allowedTypes = ["image/jpeg", "image/png"];
      if (allowedTypes.includes(file.type)) {
        const myBlob = new Blob([file], { type: file.type });
      
        const formData = new FormData();
        formData.set("aplicantCertificate", myBlob, file.name);
      }

    // Dividir el nombre y apellido
      const first_name = applicant_name.split(' ')[0];  // Primer nombre
      const second_name = applicant_name.split(' ')[1] || null; // Segundo nombre (si existe, si no asignamos null)
      const third_name = applicant_name.split(' ')[2] || null;  // Tercer nombre (si existe, si no asignamos null)
      const first_lastname = applicant_last_name.split(' ')[0];  // Primer apellido
      const second_lastname = applicant_last_name.split(' ')[1] || null; // Segundo apellido (si existe, si no asignamos null)

      formData.append('applicantFirstName', first_name);
      formData.append('applicantSecondName', second_name);  // Si no hay segundo nombre, pasará null
      formData.append('applicantThirdName', third_name);    // Si no hay tercer nombre, pasará null
      formData.append('applicantFirstLastName', first_lastname);
      formData.append('applicantSecondLastName', second_lastname);  // Si no hay segundo apellido, pasará null

      for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`); // Imprime cada clave y valor en la consola
}

    if(this.validateFileSize(formData.get('applicantCertificate'))){
      
      if(this.DataCorrect(formData)){
        alert("Estamos cargando su información");


          for (let [key, value] of formData.entries()) {
            console.log(key, value);  // Muestra cada clave y valor del FormData
        }


        this.insertData(formData);
        }else{
          alert("Uno o más datos no están correctos");
        }
       
      }
      
      
    
              

    }
        
    

    static DataCorrect(formData) {
   
   

      if (
          regular_expressions.name.test(formData.get('applicantName')) &&               // Validate name
          regular_expressions.LastName.test(formData.get('applicantLastName')) &&       // Validate last name
          regular_expressions.idNum.test(formData.get('applicantIdentification')) &&             // Validate ID number
          regular_expressions.phone.test(formData.get('applicantPhoneNumber')) &&             // Validate phone number
          regular_expressions.email.test(formData.get('applicantEmail')) &&             // Validate email
          regular_expressions.address.test( formData.get('applicantDirection')) &&         // Validate address
          formData.get('applicantCertificate') != " " &&      // Ensure applicant certificate is provided
          formData.get('applicantStudyCenter') !=  " " &&     // Ensure study center is selected
          formData.get('applicantFirstChoice') != " "  &&     // Ensure first choice is selected
          formData.get('applicantSecondChoice') != " "       // Ensure second choice is selected
      ) { 

          return true;  
      } else {

        return false;
      }

   


  };

  
  static async  insertData(formData) {
    try {
        // Realizar la solicitud POST usando fetch
        const response = await fetch('../../../api/post/applicant/insertApplicant.php', {
            method: 'POST',
            body: formData  
        });

        const result = await response.json();  // Esperamos que la respuesta sea convertida a JSON


        alert(result.message);
      
        this.hideModal();
    } catch (error) {
       console.error('Error:', error);  // Manejamos el error si ocurre
       alert('Hubo un error al cargar la información');
    }
    
}

          static validateFileSize(file) {
          
           
            const maxSize = 16 * 1024 * 1024; // 16 MB en bytes

            if (file && file.size > maxSize) {
                alert("El archivo es demasiado grande. Debe ser de 16 MB o menos.");
                return false;  // Impide el envío del formulario
            }
            return true; // Permite el envío si el archivo es válido
          }

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
  
 

export{Inscription}; 