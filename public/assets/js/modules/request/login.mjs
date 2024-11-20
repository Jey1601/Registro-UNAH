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