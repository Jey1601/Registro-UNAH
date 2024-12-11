

class EnrollmentProcess {
  static path = "../../../../";

  static async verifyEnrollmentProcessStatus() {
    try {
      const response = await fetch(
        this.path +
          "api/get/enrollment/VerifyEnrollmentProcessStatus.php"
      );  
 
      if (!response.ok) {
        throw new Error("Error en la solicitud: " + response.status);
      }

      const data = await response.json();

      
      return data;
     
    } catch (error) {
        console.log(error);
      return []; 
    }
  }

  static async verifyDatesEnrollmentProcess() {
    try {
      const response = await fetch(
        this.path + "api/get/enrollment/VerifyDatesEnrollmentProcess.php"
      );  
     
      if (!response.ok) {
        throw new Error("Error en la solicitud: " + response.status);
      }

      const data = await response.json();
   
      
      return data;
     
    } catch (error) {
        console.log(error);
      return []; 
    }
  }


}


export {EnrollmentProcess};