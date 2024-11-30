import { Alert } from "../behavior/support.mjs";

class Login {
  
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
     //Call the php method to insert in the database
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
    fetch('../../../../api/post/applicant/authApplicant.php', {
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
        window.location.href = '../../../../views/admissions/results.html';
      } else {
        Alert.display("warning", "Error en la autenticacion", result.message,'../../');
      }
    });
  } catch (error) {
    console.log('Error al mandar la peticion: ',error);
  }
}

static async authAdmisionAdmin() {
  const username = document.getElementById('admissionsUser').value;
  const password = document.getElementById('admissionsPassword').value;

  const credentials = {
      "userAdmissionAdmin": username,
      "passwordAdmissionAdmin": password
  }

  try {
      fetch('../../../../api/post/admissionAdmin/authAdmissionAdmin.php', {
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

              //Redirección de admisiones
              const tokenSaved = result.token;
              const payloadDecoded = JWT.decodeToken(tokenSaved);
              let access = [];

              if (!(JWT.payloadIsEmpty(payloadDecoded))) {
                  //const payload = this.getPayloadFromToken(tokenSaved);
                  access = payloadDecoded.accessArray;

                  access.forEach(element => {
                      switch (element) {
                        case 'Fz1YeRgv':
                          window.location.href = '../../../../views/administration/upload-grades.html';
                        break;  

                        case 'lwx50K7f':
                          window.location.href = '../../../../views/administration/verify-data-applications.html';
                        break; 

                        case 'IeMfti20':
                          window.location.href = '../../../../views/administration/verify-data-applications.html';
                        break; 

                        case 'rllHaveq':
                          window.location.href = '../../../../views/administration/verify-data-applications.html';
                        break; 

                        case 'pFw9dYOw':
                          window.location.href = '../../../../views/administration/download-admitted.html';
                        break; 
                      }
                  });
              } else {
                console.log("El usuario no tiene permisos: ", payloadDecoded);
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
      
      regular_expressions.idNum.test(credentials.applicant_identification) &&             // Validate ID number
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