class Department{
    static path = '../../../../';

    static async getDepartmentsByFaculty(idFaculty) {
        
        try {
            const response = await fetch(this.path+`/api/get/department/departmentsByFaculty.php?id_Faculty=${idFaculty}`);

            if (!response.ok) {  
                throw new Error("Error en la solicitud: " + response.status);
            }

            const data = await response.json();
            
            console.log(data);
            return data;  // Retorna los departamentos

        } catch (error) {
     
            return [];  // Si hay un error, retornamos un array vacío
        }
    }

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