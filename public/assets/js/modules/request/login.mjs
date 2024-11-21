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
  const idNum = document.getElementById('id_applicant').value;
  const numReq = document.getElementById('id_application').value;

  const credentials = {
    "numID": idNum,
    "numRequest": numReq
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
        window.location.href = '../../../../views/admissions/results.html';
      } else {
        Alert.display(result.message, "danger");
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
              window.location.href = '../../../../views/administration/admissions-admin.html';
          } else {
              Alert.display(result.message, "danger");
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

}

export{Login};