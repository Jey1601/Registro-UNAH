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
          window.location.href = this.path+'/views/admissions/results.html';
        } else {
          Alert.display("warning", "Error en la autenticacion", result.message,this.path);
        }
      });
    } catch (error) {
      console.log('Error al mandar la peticion: ',error);
    }
  }

  static async authAdmisionAdmin(username,password) {
    

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
                          //Visualiza y verifica la información de los aspirantes
                          AdmissionProccess.verifyDocumentValidationAdmissionProcess();
                          
                          break; 

                        case 'IeMfti20':
                          //Busca y edia
                          window.location.href = this.path+'views/administration/admissions/verify-data-applications.html';
                         
                          break; 

                        case 'rllHaveq':
                          //Descarga la información de las aplicaciones el proceso admisión
                          AdmissionProccess.verifyDocumentValidationAdmissionProcess(); 
                        
                          break;
                        
                        case 'pFw9dYOw':
                          //Descarga la información de los aspirantes adminitos en el proceso admisión
                          AdmissionProccess.verifyDownloadApplicantAdmittedInformationAdmissionProcess()  
                        
                          break; 
                      }
                  });
              } else {
                console.log("El usuario no tiene permisos: ", payload_decoded.accessArray);
                window.location.href = this.path+'index.html';
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
                    if (access.includes('V3yWAxgH')) {
                      window.location.href = this.path+'views/administration/faculties/professors.html';
                    } else {
                      window.location.href = '../../../../index.html';
                      Alert.display("warning", "Usuario no tiene accesos.", result.message, this.path);
                    }
              } else {
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

                    if (access.includes('2izGK2WC')) {
                      window.location.href = this.path + 'views/professors/index.html';
                    } else {
                      window.location.href = this.path + 'index.html';
                      Alert.display("warning", "Usuario no tiene accesos.", result.message, this.path);
                    }

              } else {
                console.log("El usuario no tiene permisos: ", payload_decoded.accessArray);
                window.location.href = this.path+'index.html';
              }
          } else {
            Alert.display("warning", "Error en la autenticacion", result.message);
          }
      })
    } catch (error) {
      console.log('Error al mandar la peticion: ',error);
    }
  }

  static async authStudent(username,password) {
    const credentials = {
        "userStudent": username,
        "passwordStudent": password
    }

    try {
        fetch(this.path+'api/post/student/authStudent.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(credentials)
        }).then(response => response.json()).then(result => {
            if (result.success) {
                sessionStorage.setItem('token', result.token);
                sessionStorage.setItem('typeUser',result.typeUser);

            
                const token_saved = result.token;
                const payload_decoded = JWT.decodeToken(token_saved);
                let access = [];

                if (!(JWT.payloadIsEmpty(payload_decoded))) {
                 
                    access = payload_decoded.accessArray;
                 
                    if (access.includes('iAV7sDXj')) {
                      window.location.href = this.path + 'views/students/index.html';
                    } else {
                      window.location.href = this.path + 'index.html';
                      Alert.display("warning", "Usuario no tiene accesos.", result.message);
                    }
              } else {
                console.log("El usuario no tiene permisos: ", payload_decoded.accessArray);
                window.location.href = this.path+'index.html';
              }
          } else {
            Alert.display("warning", "Error en la autenticacion", result.message);
          }
      })
    } catch (error) {
      console.log('Error al mandar la peticion: ',error);
    }
  }

  static async authDIIPAdmin(username,password) {
    const credentials = {
        "userDIIPAdmin": username,
        "passwordDIIPAdmin": password
    }

    try {
        fetch(this.path+'api/post/diipAdmin/authDIIPAdmin.php', {
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
                  console.log(payload_decoded);
                  access = payload_decoded.accessArray;

                  if(access.includes('bG8uB0wH')){
                    window.location.href = this.path+'views/administration/DIPP/upload-students.html';
                  }else{
                    Alert.display("warning", "Usuario no tiene accesos.", result.message);
                   
                    setTimeout(()=>{
                      window.location.href = this.path+'index.html';
                    }, 6000)
                   
                    
                  }
                
              } else {
                console.log("El usuario no tiene permisos: ", payload_decoded.accessArray);
                window.location.href = this.path+'index.html';
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

  static logout(url='index.html'){
    sessionStorage.setItem('token','');
    window.location.href = this.path+url;
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