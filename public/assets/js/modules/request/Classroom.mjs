
import { AcademicPlanning } from "./AcademicPlanning.mjs";
class Classroom{
    
    /**
     * Renderiza un `<select>` con las opciones de aulas correspondientes a un edificio específico dentro de un centro regional.
     * Los datos de las aulas se obtienen de la función `getDataInfrastructureAcademicPlanning`. Si los datos de las aulas están disponibles, 
     * se agregan al `<select>` y se marca la primera opción como seleccionada.
     * Si no se encuentran aulas o los datos no son válidos, se muestra un mensaje de error.
     * 
     * @author Jeyson Espinal (20201001015)
     * @created 2024-11-27
     * @param {string} idSelect - El ID del elemento `<select>` donde se renderizarán las aulas.
     * @param {int} username_user_professor - El nombre de usuario del profesor para recuperar la planificación académica.
     * @param {int} id_regionalcenter - El ID del centro regional para filtrar los edificios.
     * @param {int} id_building - El ID del edificio dentro del centro regional para filtrar las aulas.
     * @returns {Promise<void>} - No retorna valor. Realiza una solicitud asíncrona para obtener los datos de las aulas y actualiza el contenido del `<select>`.
     */
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