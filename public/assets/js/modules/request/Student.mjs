import { Alert, Cell } from "../behavior/support.mjs";

class Student {
  static path = "../../../../";
  /**
   * Obtiene las secciones de clases matriculadas por un estudiante.
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-12-09
   * @param {string} idStudent - Identificador único del estudiante.
   * @returns {Promise<Array>} Una promesa que resuelve en un arreglo de secciones de clases matriculadas.
   *                            Si ocurre un error, retorna un arreglo vacío.
   * @throws {Error} Si la respuesta del servidor no es exitosa.
   */
  static async getEnrollmentClassSection(idStudent) {
    try {
      const response = await fetch(
        this.path +
          `/api/get/student/getEnrollmentClassSection.php?idStudent=${idStudent}`
      );

      if (!response.ok) {
        throw new Error("Error en la solicitud: " + response.status);
      }

      const data = await response.json();
      console.log(data);
      if (data.status != "success") {
        Alert.display('warning', "oh", data.message, this.path);
      }else{
        Alert.display('info', "oh", data.message, this.path);  
      }
      return data.enrollmentClassSections; // Retorna las clases matriculadas
    } catch (error) {
      return []; // Si hay un error, retornamos un array vacío
    }
  }





  static async  requestPasswordReset(email) {
    const data = { email: email }; 
  
    try {
      const response = await fetch(this.path+'api/post/student/Students-process-reset.php', {
        method: 'POST', 
        headers: { 
          'Content-Type': 'application/x-www-form-urlencoded', 
        },
        body: new URLSearchParams(data),
      });
  
      const responseData = await response.json(); 
      console.log(response);
      if (responseData.success) {
        console.log('Éxito:', responseData.message); 
      } else {
        console.error('Error:', responseData.message); 
      }
    } catch (error) {
      console.error('Error en la solicitud:', error); 
    }
  }


}

export { Student };
