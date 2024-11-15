import { regular_expressions } from "./configuration.mjs";

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


   
   if(this.credentiaIsCorrect(credentials)){
     alert("Estamos cargando su información");
     //Call the php method to insert in the database
   }else{
     alert("Uno o más datos no están correctos");
   }
   
   
   console.log(credentials);
           

 }



  static credentiaIsCorrect(credentials){
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