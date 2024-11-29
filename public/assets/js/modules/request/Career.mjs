class Career {




    // Método para obtener los centros regionales de la API
    static async getCareersByCenter(id_center) {
        try {
            const response = await fetch(`../../../public/api/get/career/careersByCenter.php?id_center=${id_center}`);

            if (!response.ok) {
                throw new Error("Error en la solicitud: " + response.status);
            }

            const data = await response.json();
            
            return data;  // Retorna los centros regionales
        } catch (error) {
     
            return [];  // Si hay un error, retornamos un array vacío
        }
    }

    static async updateFirstOption() {
        //Select de centro regional
        const applicantStudyCenter = document.getElementById('applicantStudyCenter');
        const firstOption = document.getElementById('applicantFirstChoice');
        
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

              //Cargamos las carreras del segundo select,eliminando la primera opción
              firstOption.addEventListener('change', () => {
                this.updateSecondOption(Careers);
              });

              // por el valor seleccionado por defecto también cargamos la segunda opción:
              this.updateSecondOption(Careers);
        } else {
            console.error("No se encontraron carreras en el centro regional o los datos no son válidos.");
        }
    }


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
}

export { Career };
