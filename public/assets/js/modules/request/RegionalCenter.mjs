import { Career } from "./Career.mjs";

class RegionalCenter {


    static async renderSelectRegionalCenters() {
        const applicantStudyCenter = document.getElementById('applicantStudyCenter');
        applicantStudyCenter.innerHTML= '';
      
        
        
        
        const regionalCenters = await this.getRegionalCenters();

        // Comprobamos que tenemos datos antes de intentar renderizarlos
        if (regionalCenters && Array.isArray(regionalCenters)) {
            let counter = 0;
            regionalCenters.forEach(center => {
                const option = document.createElement("option");
                option.value = center.id_regional_center;
                option.innerText = center.name_regional_center;
       
                
                if(counter == 0){
                    option.selected = true;    
                  
                }
                
                applicantStudyCenter.appendChild(option);

                counter++;
              
            });
            
            //Cargamos las carreras del centro regional escogido
            applicantStudyCenter.addEventListener('change',function(){
                Career.updateFirstOption();
            })
            // pero también se llama una vez por el valor de defecto
            Career.updateFirstOption();
        } else {
            console.error("No se encontraron centros regionales o los datos no son válidos.");
        }
    }

    // Método para obtener los centros regionales de la API
    static async getRegionalCenters() {
        try {
            const response = await fetch("../../../public/api/get/regionalCenter/regionalCenters.php");

            if (!response.ok) {
                throw new Error("Error en la solicitud: " + response.status);
            }

            const data = await response.json();
           
            return data;  // Retorna los centros regionales
        } catch (error) {
          
            return [];  // Si hay un error, retornamos un array vacío
        }
    }
}

export { RegionalCenter };
