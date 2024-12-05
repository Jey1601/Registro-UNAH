import { File, Alert } from "../behavior/support.mjs";

class Professor{
  static path = '../../../';

    static async getData() {
        const professorCreationForm = document.getElementById("professorCreationForm");
        // Crear un nuevo objeto FormData
        const formData = new FormData(professorCreationForm);
    
        const mayusName = formData.get("professorName");
        const mayusLastName = formData.get("professorLastName");
      
        
        // Convertir a mayúsculas y luego establecer el valor en formData
        formData.set("professorName", mayusName.toUpperCase());
        formData.set("professorLastName", mayusLastName.toUpperCase());
        
    
        // Obtener los valores de los campos del formulario
        const professor_name = formData.get("professorName");
        const professor_last_name = formData.get("professorLastName");
    
        
        const professorPictureFile = formData.get('professorPicture');
    
        const allowedTypes = ["image/jpg",  "image/jpeg",  "image/png" ];
        
        if (allowedTypes.includes(professorPictureFile.type)) {
          const myBlob = new Blob([professorPictureFile], { type: professorPictureFile.type });
    
         // const formData = new FormData();
          formData.set("professorPictureFile", myBlob, professorPictureFile.name);
        }
            
      
    
        // Dividir el nombre y apellido
        const first_name = professor_name.split(" ")[0]; // Primer nombre
        const second_name = professor_name.split(" ")[1] || ""; // Segundo nombre (si existe, si no asignamos null)
        const third_name = professor_name.split(" ")[2] || ""; // Tercer nombre (si existe, si no asignamos null)
        const first_lastname = professor_last_name.split(" ")[0]; // Primer apellido
        const second_lastname = professor_last_name.split(" ")[1] || ""; // Segundo apellido (si existe, si no asignamos null)
    
        formData.append("professorFirstName", first_name);
        formData.append("professorSecondName", second_name); // Si no hay segundo nombre, pasará null
        formData.append("professorThirdName", third_name); // Si no hay tercer nombre, pasará null
        formData.append("professorFirstLastName", first_lastname);
        formData.append("professorSecondLastName", second_lastname); // Si no hay segundo apellido, pasará null
    
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`); // Imprime cada clave y valor en la consola 
          } 
       
        const isPictureValid = await File.validateFile(professorPictureFile); 
    
        if (isPictureValid) {
          
            Alert.display('warning','Espere','Estamos cargando su información', this.path);
            this.insertData(formData, professorCreationForm);
            console.log(formData);
        }
        
        //Limpiamos el formulario
        //inscriptionForm.reset();
      }

      static async insertData(formData, form) {
        try {
          // Realizar la solicitud POST usando fetch
          const response = await fetch(
            this.path+"/api/post/facultyAdmin/createProfessor.php",
            {
              method: "POST",
              body: formData,
            }
          );
        
          const result = await response.json(); 
          
        //Revisar que devuelve el result;
         if (result.id_application == null ){
            Alert.display(result.status,'Aviso', result.message);
          }else{
            
            form.reset();
            
             Array.from(form.elements).forEach(input => {
              input.classList.remove('right-input');
            });
            Modal.hideModal('Inscription-form');
            Alert.display('success', 'Felicidades', result.message);
          }
         
        } catch (error) {
          console.log(error);
          Alert.display('error','Lamentamos decirte esto', 'Hubo un error al cargar la información');
        }
      }

}


export {Professor}