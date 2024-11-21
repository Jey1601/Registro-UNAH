import { regular_expressions } from "../behavior/configuration.mjs";
import { Modal, Alert } from "../behavior/support.mjs";


class Results {
    static modalInstance = null;

    static verify() {
        Modal.showModal('verifySelection');
    }

    static async  getSelection() {


        const resultsForm = document.getElementById("resultsForm");
        // Crear un nuevo objeto FormData
        const formData = new FormData(resultsForm);
        const selection = document.querySelector('input[name="option"]:checked');
        const submitBtn = document.getElementById('submitBtn');

        if(selection === null){
            formData.append('option',0);
        }

        
        const primaryResolution = document.getElementById('resolution1').getAttribute("data-resolution");
        const secondaryResolution = document.getElementById('resolution2').getAttribute("data-resolution");
        console.log(formData.get('option'));
        console.log(primaryResolution);
        console.log(secondaryResolution);

        formData.append("primaryResolution", primaryResolution);
        formData.append("secondaryResolution", secondaryResolution);

        
         
            Modal.hideModal();
            

            const success = await this.registerAcceptance(formData);
            
            if (success) {
                submitBtn.remove();

                Alert.display(success.message, "success"); 
                setTimeout(() => {
                    window.location.href = '../../index.html';
                }, 2000);
            } else {
                submitBtn.disabled = false;
                submitBtn.textContent = "Enviar";
                Alert.display(success.message, "danger");
            }


            

     
    }

    static async registerAcceptance(formData){

          

        try {
        
            const response = await fetch(
              "../../../api/post/applicant/applicantAcceptance.php",
              {
                method: "POST", 
                body: formData,
              }
            );
        
            const result = await response.json();
            return result;
         
          } catch (error) {
         // Manejamos el error si ocurre
            Alert.display(result.message, "danger");
          }
    }
    
    
}

export { Results };
