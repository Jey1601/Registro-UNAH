import { Alert } from "../behavior/support.mjs";
import { AdmissionProccess } from "./AdmissionProcces.mjs";
class Login {
  static path = '../../../../';
  
  static getDataApplicant(){
      

    //Se obtiene la data del aplicante desde el login
    const applicant_identification = document.getElementById('applicantId').value;
    const applicant_application_number = document.getElementById('applicationNumber').value;

    const credentials = {
      applicant_identification,
      applicant_application_number
    };

    if(this.regexValidation(credentials)){
      alert("Estamos cargando su información");
    }else{
      alert("Uno o más datos no están correctos");
    }
    console.log(credentials);
  }

  static async authApplicant() {
    const username = document.getElementById('id_applicant').value;
    const password = document.getElementById('id_application').value;

    const credentials = {
      "username": username,
      "password": password
    }

    try{
      fetch(this.path+'api/post/applicant/authApplicant.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(credentials)
      }).then(response => response.json()).then(result => {
        console.log(result);

        if (result.success) {
          sessionStorage.setItem('token', result.token);
          sessionStorage.setItem('typeUser',result.typeUser);
          //const token = sessionStorage.getItem('token'); // Obtén el token del sessionStorage
          window.location.href = '../../../../public/views/admissions/results.html';
        } else {
          Alert.display("warning", "Error en la autenticacion", result.message,'../../');
        }
      });
    } catch (error) {
      console.log('Error al mandar la peticion: ',error);
    }
  }

  static async authAdmisionAdmin(username,password) {
    //const username = document.getElementById('admissionsUser').value;
   // const password = document.getElementById('admissionsPassword').value;

    const credentials = {
        "userAdmissionAdmin": username,
        "passwordAdmissionAdmin": password
    }

    try {
        fetch(this.path+'api/post/admissionAdmin/authAdmissionAdmin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(credentials)
        }).then(response => response.json()).then(result => {
            if (result.success) {
           
                sessionStorage.setItem('token', result.token);
                sessionStorage.setItem('typeUser',result.typeUser);

                //Redirección de admisiones
                const token_saved = result.token;
                const payload_decoded = JWT.decodeToken(token_saved);
                let access = [];

                if (!(JWT.payloadIsEmpty(payload_decoded))) {
                    access = payload_decoded.accessArray;

                  access.forEach(element => {
                      switch (element) {
                        //Carga de las notas de los exámenes de admisión de los solicitantes.
                        case 'Fz1YeRgv':
                          AdmissionProccess.verifyRegistrationRatingAdmissionProcess();
                        
                          break;  
                        
                        case 'lwx50K7f':
                          //Visualiza, busca y edita la información de los aspirantes.
                          AdmissionProccess.verifyDocumentValidationAdmissionProcess();
                          
                          break; 

                        case 'IeMfti20':
                          //Descarga la información de las aplicaciones el proceso admisión
                          window.location.href = this.path+'views/administration/verify-data-applications.html';
                          
                          break; 

                        case 'rllHaveq':
                          //Descarga la información de los aspirantes adminitos en el proceso admisión
                          AdmissionProccess.verifyDocumentValidationAdmissionProcess(); 
                        
                          break;
                        
                        case 'pFw9dYOw':
                          AdmissionProccess.verifyDownloadApplicantAdmittedInformationAdmissionProcess()  
                        
                          break; 
                      }
                  });
              } else {
                console.log("El usuario no tiene permisos: ", payload_decoded.accessArray);
                window.location.href = '../../../../index.html';
              }
          } else {
            Alert.display("warning", "Error en la autenticacion", result.message);
          }
      })
    } catch (error) {
      console.log('Error al mandar la peticion: ',error);
    }
  }

  static async authFacultyAdmin(username,password) {
    const credentials = {
        "userFacultyAdmin": username,
        "passwordFacultyAdmin": password
    }

    try {
        fetch(this.path+'api/post/facultyAdmin/authFacultyAdmin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(credentials)
        }).then(response => response.json()).then(result => {
            if (result.success) {
                sessionStorage.setItem('token', result.token);
                sessionStorage.setItem('typeUser',result.typeUser);

                //Redirección de admisiones
                const token_saved = result.token;
                const payload_decoded = JWT.decodeToken(token_saved);
                let access = [];

                if (!(JWT.payloadIsEmpty(payload_decoded))) {
                    access = payload_decoded.accessArray;

                  access.forEach(element => {
                      switch (element) {
                        //Carga de las notas de los exámenes de admisión de los solicitantes.
                        case 'V3yWAxgH':
                          window.location.href = '../../../../views/administration/faculties/professors.html';
                        
                          break;  
                        
                        default:
                          window.location.href = '../../../../index.html';
                          Alert.display("warning", "Usuario no tiene accesos.", result.message);
                          break;
                      }
                  });
              } else {
                console.log("El usuario no tiene permisos: ", payload_decoded.accessArray);
                window.location.href = '../../../../index.html';
              }
          } else {
            Alert.display("warning", "Error en la autenticacion", result.message);
          }
      })
    } catch (error) {
      console.log('Error al mandar la peticion: ',error);
    }
  }

  static async authProfessor(username,password) {
    const credentials = {
        "userProfessor": username,
        "passwordProfessor": password
    }

    try {
        fetch(this.path+'api/post/professor/authProfessor.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(credentials)
        }).then(response => response.json()).then(result => {
            if (result.success) {
                sessionStorage.setItem('token', result.token);
                sessionStorage.setItem('typeUser',result.typeUser);

                //Redirección de admisiones
                const token_saved = result.token;
                const payload_decoded = JWT.decodeToken(token_saved);
                let access = [];

                if (!(JWT.payloadIsEmpty(payload_decoded))) {
                    access = payload_decoded.accessArray;

                  access.forEach(element => {
                      switch (element) {
                        //Carga de las notas de los exámenes de admisión de los solicitantes.
                        case '2izGK2WC':
                          window.location.href = '../../../../views/professors/index.html';
                        
                          break;  
                        
                        default:
                          window.location.href = '../../../../index.html';
                          Alert.display("warning", "Usuario no tiene accesos.", result.message);
                          break;
                      }
                  });
              } else {
                console.log("El usuario no tiene permisos: ", payload_decoded.accessArray);
                window.location.href = '../../../../index.html';
              }
          } else {
            Alert.display("warning", "Error en la autenticacion", result.message);
          }
      })
    } catch (error) {
      console.log('Error al mandar la peticion: ',error);
    }
  }

  static regexValidation(credentials){
    if (
      regular_expressions.idNum.test(credentials.applicant_identification) && // Validate ID number
      credentials.applicant_application_number != " "      
    ) {
        return true;  // If all validations pass, return true
    } else {
      return false; // If any validation fails, return false
    }

  }

  static getPayloadFromToken(token) {
    const payloadBase64 = token.split('.')[1]; // Obtén el payload
    const payload = atob(payloadBase64); // Decodifica de Base64
    return JSON.parse(payload); // Convierte el JSON a un objeto
  }

  static logout(url){
    sessionStorage.setItem('token','');
    window.location.href = url;
  }

  static  updateUserType(value) {
    document.getElementById("userType").value = value;
  }

}

class JWT {
  static base64UrlDecode(base64Url) {
    const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    const decoded = atob(base64); // Decodifica la base64 string
    return decodeURIComponent(
        decoded
            .split('')
            .map(char => `%${('00' + char.charCodeAt(0).toString(16)).slice(-2)}`)
            .join('')
    );
  }

  static decodeToken (token) {
    const [header, payload, signature] = token.split('.');
    // Decodifica el payload
    const decodedPayload = this.base64UrlDecode(payload);
    // Convierte el payload a un objeto JSON
    return JSON.parse(decodedPayload);
  }

  static payloadIsEmpty(payload) {
    return (Object.keys(payload).length === 0);
  }
}

export{Login};