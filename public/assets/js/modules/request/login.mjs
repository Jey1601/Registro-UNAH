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
        window.location.href = '../../../../views/admissions/results.html';
      } else {
        Alert.display("warning", "Error en la autenticacion", result.message);
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
      fetch('../../../api/post/admissionAdmin/authAdmissionAdmin.php', {
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
              const tokenSaved = sessionStorage.getItem('token'); // Obtén el token del sessionStorage

              let access = [];

              if (tokenSaved) {
                  const payload = this.getPayloadFromToken(tokenSaved);
                  access = payload.accessArray;

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

export{Login};