import { AcademicPlanning } from "./AcademicPlanning.mjs";
import { Alert } from "../behavior/support.mjs";
class Class{

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

    static updateCode(idinput,code,){
        const input = document.getElementById(idinput);
        input.value = code;
    }
    

}


export {Class}