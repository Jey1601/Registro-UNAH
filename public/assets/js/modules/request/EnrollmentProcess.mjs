import { Alert } from "../behavior/support.mjs";

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


  static async getPendingClassesStudent(studentId){
    const data = {
      student_id: studentId
    };

   
    try {
      const response = await fetch(
        this.path +
          "api/post/enrollment/PostPendingClassesStudent.php",
        {
          method: "POST",  
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );

      const responseData = await response.json();
      console.log("Respuesta del servidor:", responseData);

      
      if (responseData.status != "success") {
        Alert.display(
          responseData.status,
          "oh",
          'Algo anda mal',
          this.path
        )
      } 

     return responseData.data;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }    
  } 


  static async getClassSectionsForStudent(student_id,class_id ){
    const data = {
      student_id:student_id, 
      class_id :class_id
    };

   
    try {
      const response = await fetch(
        this.path +
          "api/post/enrollment/PostClassSectionsForStudent.php",
        {  
          method: "POST",  
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );

      const responseData = await response.json();
      console.log("Respuesta del servidor:", responseData);

      
      if (responseData.status != "success") {
        Alert.display(
          responseData.status,
          "oh",
          'Algo anda mal',
          this.path
        )
      } 
     console.log(response); 
     return responseData.data;
    } catch (error) {
      console.error("Error:", error);
      return null;
    }    
  } 



static    populateDepartments(data,departmentSelect ) {
  departmentSelect.innerHTML ='';
  departmentSelect.innerHTML = '<option value="">Seleccione un departamento</option>';
  // Iterar sobre los departamentos
  for (const departmentName of Object.keys(data)) {
    const option = document.createElement('option');
    option.value = departmentName;
    option.textContent = departmentName;
    departmentSelect.appendChild(option);
  }
}

// Función para llenar las opciones de las clases según el departamento seleccionado
static populateClasses(data,department, classSelect) {
  
  classSelect.innerHTML = '';
  // Limpiar las opciones anteriores del select de clases
  classSelect.innerHTML = '<option value="">Seleccione una clase</option>';

  // Si no hay un departamento seleccionado, deshabilitar el select de clases
  if (!department || !data[department]) {
    classSelect.disabled = true;
    return;
  }

  // Habilitar el select de clases
  classSelect.disabled = false;

  // Agregar las opciones de clases correspondientes al departamento
  const classes = data[department];
  classes.forEach(classItem => {
    const option = document.createElement('option');
    option.value = classItem.ClassID;
    option.textContent = classItem.ClassName;
    classSelect.appendChild(option);
  });
}




  
}


export {EnrollmentProcess};