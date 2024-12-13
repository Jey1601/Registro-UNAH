import { AcademicPlanning } from "./AcademicPlanning.mjs";
import { Alert } from "../behavior/support.mjs";
class Class{

    /**
 * Renderiza un `<select>` con las opciones de clases correspondientes a una carrera y periodicidad académica específica.
 * Las clases se obtienen desde una fuente de datos a través de la función `postDataClassesAcademicPlanning`.
 * Si las clases están disponibles, se agregan al `<select>` y se marca la primera clase como seleccionada.
 * Además, se actualiza el código de la clase seleccionada utilizando `updateCode`.
 * Si no se encuentran clases o los datos no son válidos, se muestra un mensaje de error.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-11-27
 * 
 * @param {string} idSelect - El ID del elemento `<select>` donde se renderizarán las clases.
 * @param {int} idUndergraduate - El ID de la carrera para la que se desean obtener las clases.
 * @param {int} academicPeriodicity - La periodicidad académica para filtrar las clases.
 * 
 * @returns {Promise<void>} - No retorna valor. Realiza una solicitud asíncrona para obtener las clases y actualiza el contenido del `<select>`.
 * 
 * @example
 * renderSelectClassesForPlanning('classSelect', 1, 2);
 */
    static async renderSelectClassesForPlanning(idSelect, idUndergraduate, academicPeriodicity) {
        const select = document.getElementById(idSelect);
        select.innerHTML= '';
      
      
    
        //Eliminamos el contenido que pueda tener el select de carrera principal
        select.innerHTML = '';
        
        
        const classes = await AcademicPlanning.postDataClassesAcademicPlanning(idUndergraduate, academicPeriodicity);
        
        // Comprobamos que tenemos datos antes de intentar renderizarlos
        if (classes && Array.isArray(classes)) {
            let counter = 0;
            classes.forEach(asignature => {
                const option = document.createElement("option");
                option.value = asignature.id_class;
                option.innerText = asignature.name_class;
       
                
                if(counter == 0){
                    option.selected = true;    
                    this.updateCode('classCode', asignature.id_class);
                }
                
                select.appendChild(option);

                counter++;
              
            });
            
           
        } else {
            Alert.display('error','Lo sentimos','No se encontraron clases','../../../.././');
           
        }
    }
    
    /**
     * Actualiza el valor del input que muestra el código de la clase proporcionado.
     * 
     * @author Jeyson Espinal (20201001015)
     * @created 2024-11-27
     * 
     * @param {string} idinput - El ID del elemento `<input>` cuyo valor se actualizará.
     * @param {string} code - El código que se asignará al campo de entrada.
     * 
     * @returns {void} - No retorna valor. Solo actualiza el valor del campo de entrada con el código proporcionado.
     * 
     */
    static updateCode(idinput,code,){
        const input = document.getElementById(idinput);
        input.value = code;
    }
    

}


export {Class}