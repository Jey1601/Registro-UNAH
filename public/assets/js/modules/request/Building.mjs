import { AcademicPlanning } from "./AcademicPlanning.mjs";
import { Alert } from "../behavior/support.mjs";
import { Classroom } from "./Classroom.mjs";

class Building{

    static async renderSelectBuildingsByCenter(idSelect, username_user_professor, id_regionalcenter) {
        const select = document.getElementById(idSelect);
        select.innerHTML= '';
      
      
    
        //Eliminamos el contenido que pueda tener el select de carrera principal
        select.innerHTML = '';
        
        
        const buildings = await AcademicPlanning.getDataInfrastructureAcademicPlanning(username_user_professor, id_regionalcenter);
        
        // Comprobamos que tenemos datos antes de intentar renderizarlos
        if (buildings && Array.isArray(buildings)) {

          
       
            const addedIds = new Set(); 

            buildings.forEach(building => {
                if (!addedIds.has(building.id_building)) { // Verifica si el ID ya fue agregado
                    const option = document.createElement("option");
                    option.value = building.id_building;
                    option.innerText = building.name_building;

                    if (addedIds.size === 0) { // Si es la primera opción, seleccionarla
                        option.selected = true;
                        Classroom.renderSelectClassroomsByCenter('classroom', username_user_professor,id_regionalcenter,building.id_building);
                    }

                    select.appendChild(option);
                    addedIds.add(building.id_building); // Agrega el ID al Set
                }
            });


           
        } else {
            Alert.display('error','Lo sentimos','No se encontraron Edificios','../../../.././');
            console.error("No se encontraron Edificios o los datos no son válidos.");
        }
    }

}

export {Building}