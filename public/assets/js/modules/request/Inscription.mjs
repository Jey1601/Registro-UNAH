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

    const file = formData.get("aplicantCertificate");

    const allowedTypes = ["image/jpg", "image/png"];
    if (allowedTypes.includes(file.type)) {
      const myBlob = new Blob([file], { type: file.type });

      const formData = new FormData();
      formData.set("aplicantCertificate", myBlob, file.name);
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

    if (this.validateFileSize(formData.get("applicantCertificate"))) {
      if (this.DataCorrect(formData)) {
        Alert.display("Estamos cargando su información", "success");

        this.insertData(formData);
      } else {
        Alert.display("Uno o más datos no están correctos", "danger");
      }
    }

    //Limpiamos el formulario
    inscriptionForm.reset();
  }

  static DataCorrect(formData) {
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
  }

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
        Alert.display(result.message , "warning");
      }else{
        Alert.display(result.message.concat("<br> Numero de solicitud : ", result.id_application ), "warning");
      }
     
    } catch (error) {
      Alert.display("Hubo un error al cargar la información", "danger");
    }
  }

  static validateFileSize(file) {
    const maxSize = 16 * 1024 * 1024; // 16 MB en bytes

    if (file && file.size > maxSize) {
      Alert.display(
        "El archivo es demasiado grande. Debe ser de 16 MB o menos",
        "danger"
      );
      return false; // Impide el envío del formulario
    }
    return true; // Permite el envío si el archivo es válido
  }
}



export { Inscription };
