import { Alert } from "../behavior/support";

class Login {
  
  static getDataApplicant(){
      

    //Obtaining the applicant data from the login form
    const applicant_identification = document.getElementById('applicantId').value;
    const applicant_application_number = document.getElementById('applicationNumber').value;


   // Creating an object with the information
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

  static async authRequestAdmissionAdmin() {
    const credentials = {
      userAdmissionAdmin: document.getElementById('admissionsUser').value,
      passwordAdmissionAdmin: document.getElementById('admissionsPassword').value
    };
    
    fetch('../../../api/post/admissionAdmin', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(credentials)
    }).then(response => response.json()).then(result => {
      if (result.success) {
        sessionStorage.setItem('token', result.token);
        window.location.href = '../../../views/administration/admissions-admin.html';
      } else {
        Alert.display(result.message, 'warning');
      }
    }).catch(error => {
      console.log("Peticion fallida: ", error);
    });
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