
import { AcademicPlanning } from "./AcademicPlanning.mjs";
class Classroom{


    static async  renderSelectClassroomsByCenter(idSelect, username_user_professor, id_regionalcenter, id_building) {
        const select = document.getElementById(idSelect);
        select.innerHTML= '';
      
      
    
        //Eliminamos el contenido que pueda tener el select de carrera principal
        select.innerHTML = '';
        
        const buildings = await AcademicPlanning.getDataInfrastructureAcademicPlanning(username_user_professor, id_regionalcenter);

        let classrooms = [];

        buildings.forEach(building =>{
            if(building.id_building == id_building ){
                classrooms.push({ 
                    id_classroom: building.id_classroom,
                    name_classroom: building.name_classroom
                });
            }
        })

        // Comprobamos que tenemos datos antes de intentar renderizarlos
        if (classrooms && Array.isArray(classrooms)) {

            
       
            const addedIds = new Set(); 

            classrooms.forEach(classroom => {
                if (!addedIds.has(classroom.id_classroom)) { // Verifica si el ID ya fue agregado
                    const option = document.createElement("option");
                    option.value = classroom.id_classroom;
                    option.innerText = classroom.name_classroom;

                    if (addedIds.size === 0) { // Si es la primera opción, seleccionarla
                        option.selected = true;
                    }

                    select.appendChild(option);
                    addedIds.add(classroom.id_classroom); // Agregar el ID al Set
                }
            });


           
        } else {
            Alert.display('error','Lo sentimos','No se encontraron aulas','../../../.././');
            console.error("No se encontraron aulas o los datos no son válidos.");
        }
    }

}

export {Classroom}