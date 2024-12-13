class Department{
    static path = '../../../../';


    /**
     * Obtiene los departamentos de una facultad específica a través de una solicitud a la API.
     * Si la solicitud es exitosa, retorna los departamentos correspondientes a la facultad proporcionada.
     * En caso de error, retorna un arreglo vacío.
     * 
     * @author Jeyson Espinal (20201001015)
     * @created 2024-11-27
     * @param {int} idFaculty - El ID de la facultad para obtener los departamentos correspondientes.
     * @returns {Promise<Array>} - Retorna una promesa que resuelve un arreglo de departamentos, o un arreglo vacío si ocurre un error.
     */
    static async getDepartmentsByFaculty(idFaculty) {
        
        try {
            const response = await fetch(this.path+`/api/get/department/departmentsByFaculty.php?id_Faculty=${idFaculty}`);

            if (!response.ok) {  
                throw new Error("Error en la solicitud: " + response.status);
            }

            const data = await response.json();
            
         
            return data;  // Retorna los departamentos

        } catch (error) {
     
            return [];  // Si hay un error, retornamos un array vacío
        }
    }
    
    /**
     * Renderiza un `<select>` con las opciones de departamentos correspondientes a una facultad específica.
     * Los departamentos se obtienen desde una fuente de datos a través de la función `getDepartmentsByFaculty`.
     * Si los departamentos están disponibles, se agregan al `<select>` y se marca el primero como seleccionado.
     * Si no se encuentran departamentos o los datos no son válidos, se muestra un mensaje de error en consola.
     * 
     * @author Jeyson Espinal (20201001015)
     * @created 2024-11-27
     * @param {string} idSelect - El ID del elemento `<select>` donde se renderizarán los departamentos.
     * @param {int} idFaculty - El ID de la facultad para obtener los departamentos correspondientes.
     * @returns {Promise<void>} - No retorna valor. Realiza una solicitud asíncrona para obtener los departamentos y actualiza el contenido del `<select>`.
     */
    static async renderSelectDepartmentsByFaculty(idSelect,idFaculty) {
        const select = document.getElementById(idSelect);
        select.innerHTML= '';
      
        
        
        
        const Departments = await this.getDepartmentsByFaculty(idFaculty);

        // Comprobamos que tenemos datos antes de intentar renderizarlos
        if (Departments && Array.isArray(Departments)) {
            let counter = 0;
            Departments.forEach(Department => {
                const option = document.createElement("option");
                option.value = Department.id_department;
                option.innerText = Department.name_departmet;
       
                
                if(counter == 0){
                    option.selected = true;    
                  
                }
                
                select.appendChild(option);

                counter++;
              
            });
            
          
        } else {
            console.error("No se encontraron Departamentos o los datos no son válidos.");
        }
    }

}

export {Department}