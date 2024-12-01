import { regular_expressions } from "../behavior/configuration.mjs";
import { Alert, Modal, File } from "../behavior/support.mjs";

class Inscription {
  static path = '../../../../'

  static async getData() {
    const inscriptionForm = document.getElementById("inscriptionForm");
    // Crear un nuevo objeto FormData
    const formData = new FormData(inscriptionForm);

    const mayusName = formData.get("applicantName");
    const mayusLastName = formData.get("applicantLastName");
    const mayusAddres = formData.get("applicantDirection");
    
    // Convertir a mayúsculas y luego establecer el valor en formData
    formData.set("applicantName", mayusName.toUpperCase());
    formData.set("applicantLastName", mayusLastName.toUpperCase());
    formData.set("applicantDirection", mayusAddres.toUpperCase());

    // Obtener los valores de los campos del formulario
    const applicant_name = formData.get("applicantName");
    const applicant_last_name = formData.get("applicantLastName");

    
    const certificateFile = formData.get("applicantCertificate");
    const idFile = formData.get('applicantIdDocument');

    const allowedTypes = ["image/jpg",  "image/jpeg",  "image/png" ,"application/pdf"];
    
    if (allowedTypes.includes(certificateFile.type)) {
      const myBlob = new Blob([certificateFile], { type: certificateFile.type });

     // const formData = new FormData();
      formData.set("applicantCertificate", myBlob, certificateFile.name);
    }
        
    if (allowedTypes.includes(idFile.type)) {
      const myBlob = new Blob([idFile], { type: idFile.type });

      //const formData = new FormData();
      formData.set("applicantCertificate", myBlob, idFile.name);
    }

    // Dividir el nombre y apellido
    const first_name = applicant_name.split(" ")[0]; // Primer nombre
    const second_name = applicant_name.split(" ")[1] || ""; // Segundo nombre (si existe, si no asignamos null)
    const third_name = applicant_name.split(" ")[2] || ""; // Tercer nombre (si existe, si no asignamos null)
    const first_lastname = applicant_last_name.split(" ")[0]; // Primer apellido
    const second_lastname = applicant_last_name.split(" ")[1] || ""; // Segundo apellido (si existe, si no asignamos null)

    formData.append("applicantFirstName", first_name);
    formData.append("applicantSecondName", second_name); // Si no hay segundo nombre, pasará null
    formData.append("applicantThirdName", third_name); // Si no hay tercer nombre, pasará null
    formData.append("applicantFirstLastName", first_lastname);
    formData.append("applicantSecondLastName", second_lastname); // Si no hay segundo apellido, pasará null

    /*for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`); // Imprime cada clave y valor en la consola 
      } */
    const isCertificateValid = await File.validateFile(certificateFile);
    const isIdValid = await File.validateFile(idFile); 

    if (isCertificateValid && isIdValid) {
      
        Alert.display('warning','Espere','Estamos cargando su información');
        this.insertData(formData, inscriptionForm);
      
    }
    
    //Limpiamos el formulario
    //inscriptionForm.reset();
  }

 /* static DataCorrect(formData) {
    if (
      regular_expressions.name.test(formData.get("applicantName")) && // Validate name
      regular_expressions.LastName.test(formData.get("applicantLastName")) && // Validate last name
      regular_expressions.idNum.test(formData.get("applicantIdentification")) && // Validate ID number
      regular_expressions.phone.test(formData.get("applicantPhoneNumber")) && // Validate phone number
      regular_expressions.email.test(formData.get("applicantEmail")) && // Validate email
      regular_expressions.address.test(formData.get("applicantDirection")) && // Validate address
      formData.get("applicantCertificate") != " " && // Ensure applicant certificate is provided
      formData.get("applicantStudyCenter") != " " && // Ensure study center is selected
      formData.get("applicantFirstChoice") != " " && // Ensure first choice is selected
      formData.get("applicantSecondChoice") != " " // Ensure second choice is selected
    ) {
      return true;
    } else {
      return false;
    }
  }*/

    static async setConfirmationEmailApplicants() {
      const inscriptionForm = document.getElementById("inscriptionForm");
      // Crear un nuevo objeto FormData
      const formData = new FormData(inscriptionForm);


      try {
        // Realizar la solicitud POST usando fetch
        const response = await fetch(
           this.path+"/api/post/applicant/verifyEmail.php",
          {
            method: "POST",
            body: formData,
          }
        );
        
        const result = await response.json(); // Esperamos que la respuesta sea convertida a JSON
        

        
          Alert.display( result.status, 'Aviso', result.message);
        
       
      } catch (error) {
        console.log(error);
        Alert.display('error', 'Algo ha salido mal', 'No hemos podido enviar el código de confirmación,intentalo más tarde');
      }
    }


      // Método para confirmar el correo 
      static async getConfirmationEmailApplicants() {
        const emailCodeVerificationInput = document.getElementById('emailCodeVerification');
        const emailCodeVerification = emailCodeVerificationInput.value;
        const applicantIdentification = document.getElementById('applicantIdentification').value;
       
        try {
          const response = await fetch(this.path+`/api/get/applicant/verifyEmail.php?applicantIdentification=${encodeURIComponent(applicantIdentification)}&emailCodeVerification=${encodeURIComponent(emailCodeVerification)}`);
          
          if (!response.ok) {
                throw new Error("Error en la solicitud: " + response.status);
            }
            const result = await response.json();
    
            if(result.status === 'success'){

              Alert.display(result.status, 'Gracias', result.message, '../../../');
              emailCodeVerificationInput.value='';
              Modal.hideModal('modalEmailCodeVerification');
              this.getData();
            }else{
              Alert.display(result.status, 'Algo ha salido mal', result.message);
            }
        } catch (error) {
     
          console.log(error);
          Alert.display('error', 'Algo ha salido mal', 'No hemos podido confirmar el código de confirmación,intentalo más tarde');
        }
    }

  static async insertData(formData, form) {
    try {
      // Realizar la solicitud POST usando fetch
      const response = await fetch(
        this.path+"/api/post/applicant/insertApplicant.php",
        {
          method: "POST",
          body: formData,
        }
      );

      const result = await response.json(); 
     
      if (result.id_application == null ){
        Alert.display(result.status,'Aviso', result.message);
      }else{
        
        form.reset();
        
         Array.from(form.elements).forEach(input => {
          input.classList.remove('right-input');
        });
        Modal.hideModal('Inscription-form');
        Alert.display('success', 'Felicidades', result.message.concat(" Numero de solicitud : ", result.id_application ));
      }
     
    } catch (error) {
      console.log(error);
      Alert.display('error','Lamentamos decirte esto', 'Hubo un error al cargar la información');
    }
  }





}



export { Inscription };
