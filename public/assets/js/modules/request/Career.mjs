import { AcademicPlanning } from "./AcademicPlanning.mjs";
import { Alert } from "../behavior/support.mjs";
import { Class } from "./Class.mjs";
class Career {


        static path = '../../../../'
    /**
     * Obtiene una lista de carreras asociadas a un centro específico a través de una solicitud API.
     * La solicitud se realiza mediante `fetch` a un endpoint que devuelve las carreras disponibles para un centro regional determinado.
     * Si la solicitud es exitosa, los datos se retornan en formato JSON. En caso de error, se retorna un array vacío.
     *
     * @author Jeyson Espinal (20201001015)
     * @created 2024-11-07
     * @param {int} id_center - El ID del centro regional para obtener las carreras asociadas.
     * @returns {Promise<Array<Object>>} - Retorna una promesa que resuelve en un array de objetos que representan las carreras del centro. En caso de error, se retorna un array vacío.
     */
    // Método para obtener los centros regionales de la API
    static async getCareersByCenter(id_center) {
        try {
            const response = await fetch(this.path+`/api/get/career/careersByCenter.php?id_center=${id_center}`);

            if (!response.ok) {
                throw new Error("Error en la solicitud: " + response.status);
            }

            const data = await response.json();
            
            return data;  // Retorna los centros regionales
        } catch (error) {
     
            return [];  // Si hay un error, retornamos un array vacío
        }
    }

    /**
 * Actualiza el primer select de carreras basado en el centro regional seleccionado.
 * Esta función obtiene las carreras disponibles para un centro regional específico y actualiza el select correspondiente
 * con las opciones correspondientes. Si se selecciona una carrera, también se activa la funcionalidad para cargar
 * opciones en un segundo select (si es el caso del módulo de Admisiones).
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-11-07
 * @param {string} [idCarrer='applicantFirstChoice'] - El ID del select de carrera principal a actualizar.
 * @param {string} [idCenter='applicantStudyCenter'] - El ID del select de centro regional desde donde se seleccionan las carreras.
 * @param {string} [module='Admissions'] - El módulo donde se ejecuta la función. Si es 'Admissions', se activará la actualización de un segundo select.
 * @returns {Promise<void>} - No retorna nada. Realiza una actualización visual en la interfaz del usuario.
 */
    static async updateFirstOption(idCarrer='applicantFirstChoice', idCenter ="applicantStudyCenter",module ='Admissions') {
        //Select de centro regional
        const applicantStudyCenter = document.getElementById(idCenter);
        const firstOption = document.getElementById(idCarrer);
        
        //Tomamos el valor seleccionado del select de centro regional
        const selectedValue = applicantStudyCenter.value;
        
        //Eliminamos el contenido que pueda tener el select de carrera principal
        firstOption.innerHTML = '';


        const Careers = await this.getCareersByCenter(selectedValue);

        // Comprobamos que tenemos datos antes de intentar renderizarlos
        if (Careers && Array.isArray(Careers)) {
            let counter = 0;
            Careers.forEach(career => {
                const option = document.createElement("option");
                option.value = career.id_undergraduate;
                option.innerText = career.name_undergraduate;
                if(counter == 0){
                    option.selected = true;
                }

                firstOption.appendChild(option);
                
                counter++;
            });

            if(module == 'Admissions') {
            //Cargamos las carreras del segundo select,eliminando la primera opción
              firstOption.addEventListener('change', () => {
                this.updateSecondOption(Careers);
              });
            
              // por el valor seleccionado por defecto también cargamos la segunda opción:
              this.updateSecondOption(Careers);
            }
        } else {
            console.error("No se encontraron carreras en el centro regional o los datos no son válidos.");
        }
    }

    /**
     * Actualiza el segundo select de carreras basado en la selección del primer select (carrera principal).
     * Esta función elimina la carrera seleccionada en el primer select de la lista de opciones disponibles para el segundo select,
     * y actualiza el contenido de este segundo select con las carreras restantes.
     * 
     * @author Jeyson Espinal (20201001015)
     * @created 2024-11-07
     * @param {Array} Careers - Un arreglo de objetos con las carreras disponibles para el centro regional.
     * @returns {void} - No retorna nada, solo actualiza el segundo select visualmente.
     */
    static    updateSecondOption(Careers) {
        //Select de la primera carrera
        const firstOption = document.getElementById('applicantFirstChoice');
        const secondOption =document.getElementById('applicantSecondChoice');

        //Tomamos el valor seleccionado del select de carrera principal
        const selectedValue = firstOption.value;

        //Eliminamos el contenido que pueda tener el select de carrera secundaria
        secondOption.innerHTML = '';

           
            Careers.forEach(career => {
                if(career.id_undergraduate != selectedValue){

                    const option = document.createElement("option");
                    option.value = career.id_undergraduate;
                    option.innerText = career.name_undergraduate;

                    secondOption.appendChild(option);
                 }
            });
    }


    /**
 * Renderiza un `<select>` con las opciones de edificios correspondientes a un centro regional específico.
 * Los edificios se obtienen desde una fuente de datos a través de la función `getDataInfrastructureAcademicPlanning`.
 * Si los edificios están disponibles, se agregan al `<select>` y se marca el primero como seleccionado.
 * Además, se actualizan las aulas disponibles para el edificio seleccionado utilizando `renderSelectClassroomsByCenter`.
 * Si no se encuentran edificios o los datos no son válidos, se muestra un mensaje de error.
 * 
 * @author Jeyson Espinal (20201001015)
 * @created 2024-11-27
 * 
 * @param {string} idSelect - El ID del elemento `<select>` donde se renderizarán los edificios.
 * @param {int} username_user_professor - El nombre de usuario del profesor para recuperar la planificación académica.
 * @param {int} id_regionalcenter - El ID del centro regional para filtrar los edificios.
 * 
 * @returns {Promise<void>} - No retorna valor. Realiza una solicitud asíncrona para obtener los edificios y actualiza el contenido del `<select>`.
 * 
 */
    static async renderSelectUndergraduatesByCenter(idSelect, username_user_professor, id_regionalcenter) {
        const select = document.getElementById(idSelect);
        select.innerHTML= '';
      
      
    
        //Eliminamos el contenido que pueda tener el select de carrera principal
        select.innerHTML = '';
        
        
        const Undergraduates = await AcademicPlanning.UndergraduatesAcademicPlanning(username_user_professor, id_regionalcenter);
        
        // Comprobamos que tenemos datos antes de intentar renderizarlos
        if (Undergraduates && Array.isArray(Undergraduates)) {
            let counter = 0;
          
            Undergraduates.forEach(undergradute => {
                const option = document.createElement("option");
                option.value = undergradute.id_undergraduate;
                option.innerText = undergradute.name_undergraduate;
       
                
                if(counter == 0){
                    option.selected = true;    
                    Class.renderSelectClassesForPlanning('classPlanning',undergradute.id_undergraduate , 2);
                }
                
                select.appendChild(option);

                counter++;
              
            });
            
           
        } else {
            Alert.display('error','Lo sentimos','No se encontraron carreras','../../../.././');
            console.error("No se encontraron carreras o los datos no son válidos.");
        }
    }


}

export { Career };
