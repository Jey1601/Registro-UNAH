import { regular_expressions } from "../behavior/configuration.mjs";
import { Alert } from "../behavior/support.mjs";

class Inscription {
  

  static async getData() {
    const inscriptionForm = document.getElementById("inscriptionForm");
    // Crear un nuevo objeto FormData
    const formData = new FormData(inscriptionForm);

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
    const isCertificateValid = await this.validateFile(certificateFile);
    const isIdValid = await this.validateFile(idFile); 
    if (isCertificateValid && isIdValid) {
      
        Alert.display('warning','Espere','Estamos cargando su información');
        this.insertData(formData);
      
    }
    
    if(!isCertificateValid){
      document.getElementById('applicantCertificate').value='';
      Alert.display('error','Algo anda mal',"Revise el tamaño o resolución de su archivo de certificado");
    }
    
    if(!isIdValid){
      document.getElementById('applicantIdDocument').value='';
      Alert.display('error','Algo anda mal',"Revise el tamaño o resolución de su archivo de identificación");
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

  static async insertData(formData) {
    try {
      // Realizar la solicitud POST usando fetch
      const response = await fetch(
        "../../../api/post/applicant/insertApplicant.php",
        {
          method: "POST",
          body: formData,
        }
      );

      const result = await response.json(); // Esperamos que la respuesta sea convertida a JSON
      if (result.id_application== null ){
        Alert.display('warning','Aviso', result.message);
      }else{
        Alert.display('success', 'Felicidades', result.message.concat("<br> Numero de solicitud : ", result.id_application ), "warning");
      }
     
    } catch (error) {
      Alert.display('error','Lamentamo decirte esto', 'Hubo un error al cargar la información');
    }
  }

  static validateFile(file) {
    console.log(file);
    const maxSize = 16 * 1024 * 1024;  // 16 MB en bytes

    // Verificar el tamaño del archivo
    if (file.size > maxSize) {
        return Promise.resolve(false);  // Si es demasiado grande, retornamos false inmediatamente
    }else if(file.type != 'application/pdf'){
      // Crear un FileReader para leer la imagen
    const reader = new FileReader();

    // Retornamos una promesa que se resolverá después de la lectura
    return new Promise((resolve) => {
        reader.onload = function(event) {
            const img = new Image();

            img.onload = function() {
                const width = img.width;
                const height = img.height;

                // Verificar las dimensiones de la imagen
                if (width < 600 || height < 800) {
                    console.log('false');
                    console.log(`La imagen tiene dimensiones: ${width}x${height}`);
                    resolve(false);  // Resolvemos la promesa con false
                } else {
                    console.log('true');
                    console.log(`La imagen tiene dimensiones: ${width}x${height}`);
                    resolve(true);  // Resolvemos la promesa con true
                }
            };

            img.src = event.target.result;  // Cargar la imagen en el objeto Image
        };

        // Leer el archivo como URL de datos (esto activa el evento onload)
        reader.readAsDataURL(file);
    });
    }else{
      return true;
    }
    
}




}



export { Inscription };
