import { regular_expressions } from "../configuration.js";

class Inscription{

    

    static getData(){
      

       //Obtaining the applicant data from the form
       const applicant_name = document.getElementById('applicantName').value;
       const applicant_last_name = document.getElementById('applicantLastName').value;
       const applicant_identification = document.getElementById('applicantIdentification').value;
       const applicant_phone_number = document.getElementById('applicantPhoneNumber').value;
       const applicant_email = document.getElementById('applicantEmail').value;
       const applicant_direction = document.getElementById('applicantDirection').value;
       const applicant_certificate = document.getElementById('aplicantCertificate').value; 
       const applicant_study_center = document.getElementById('applicantStudyCenter').value;
       const applicant_first_choice = document.getElementById('applicantFirstChoice').value;
       const applicant_second_choice = document.getElementById('applicantSecondChoice').value;

      // Creating an object with the information
      const applicant_Data = {
        applicant_name,
        applicant_last_name,
        applicant_identification,
        applicant_phone_number,
        applicant_email,
        applicant_direction,
        applicant_certificate,
        applicant_study_center,
        applicant_first_choice,
        applicant_second_choice
      };


      
      if(this.DataCorrect(applicant_Data)){
        alert("Estamos cargando su información");
        //Call the php method to insert in the database
      }else{
        alert("Uno o más datos no están correctos");
      }
      
      
      console.log(applicant_Data);
              

    }
        
    

    static DataCorrect(applicant_Data) {
   
      // Check that all the fields pass their respective regular expression tests 
      // and that the other required fields are not null

      if (
          regular_expressions.name.test(applicant_Data.applicant_name) &&               // Validate name
          regular_expressions.LastName.test(applicant_Data.applicant_last_name) &&       // Validate last name
          regular_expressions.idNum.test(applicant_Data.applicant_identification) &&             // Validate ID number
          regular_expressions.phone.test(applicant_Data.applicant_phone_number) &&             // Validate phone number
          regular_expressions.email.test(applicant_Data.applicant_email) &&             // Validate email
          regular_expressions.address.test( applicant_Data.applicant_direction) &&         // Validate address
          applicant_Data.applicant_certificate != " " &&      // Ensure applicant certificate is provided
          applicant_Data.applicant_study_center !=  " " &&     // Ensure study center is selected
          applicant_Data.applicant_first_choice != " "  &&     // Ensure first choice is selected
          applicant_Data.applicant_second_choice != " "       // Ensure second choice is selected
      ) {
          return true;  // If all validations pass, return true
      } else {

        return false; // If any validation fails, return false
      }

   


  };


  

  } 
  
 

export{Inscription}; 