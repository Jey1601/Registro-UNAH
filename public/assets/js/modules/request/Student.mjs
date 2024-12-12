import { Alert} from "../behavior/support.mjs";

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
    try {
      // Verificamos que el correo no esté vacío
      if (!email) {
        console.error("El correo es requerido.");
        return;
      }
  
      // Enviamos la solicitud POST
      const response = await fetch(this.path+'api/post/student/Students-process-reset.php', {
        method: 'POST', 
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded', // Tipo de contenido adecuado para datos de formulario
        },
        body: new URLSearchParams({
          email: email // Pasamos el correo como parámetro
        }),
      });
  
      // Obtenemos la respuesta en formato JSON
      const responseData = await response.json();
  
      // Comprobamos si la respuesta fue exitosa
      if (responseData.success) {
        
        Alert.display('info','oh', responseData.message, this.path);
        Alert.display('success','oh', data.message, this.path);
        setTimeout(() => {
          window.close();
        }, 7000);
  
      } else {
        
        Alert.display('warning','oh', responseData.message, this.path);
      }
    } catch (error) {
      console.error('Error al realizar la solicitud:', error);
    }
  }


  static async resetPassword(token, newPassword) {
    try {
      // Verificamos que los parámetros no sean vacíos
      if (!token || !newPassword) {
        console.error("Token y nueva contraseña son requeridos.");
        return;
      }
  
      // Realizamos la solicitud POST
      const response = await fetch(this.path+'api/post/student/students-update-password.php', {
        method: 'POST',  // Método POST
        headers: {  
          'Content-Type': 'application/x-www-form-urlencoded',  // Tipo adecuado para enviar datos en formulario
        },
        body: new URLSearchParams({
          token: token,  // Enviamos el token
          new_password: newPassword  // Enviamos la nueva contraseña
        }),
      });
  
      // Obtenemos la respuesta en formato JSON
      const data = await response.json();
  
      if (data.success) {
        Alert.display('success','oh', data.message, this.path);
        setTimeout(() => {
          window.close();
        }, 7000);
      } else {
        Alert.display('warning','oh', data.message, this.path);
      }
    } catch (error) {
      console.error('Error al realizar la solicitud:', error);
    }
  }

}

export { Student };
