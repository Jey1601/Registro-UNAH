import { Alert, Modal } from "../behavior/support.mjs";
import { RegionalCenter } from "./RegionalCenter.mjs";

class AdmissionProccess{

    static async getCurrentProccess() {
    
        try {
            const response = await fetch("../../../public/api/get/admissionProccess/currentAdmissionProccess.php");
    
            if (!response.ok) {
                throw new Error("Error en la solicitud: " + response.status);
            }
    
            const data = await response.json();
     
           
           if(data.status == 'success'){
               
                RegionalCenter.renderSelectRegionalCenters();
                Modal.showModal('Inscription-form');
           }else{

                Alert.display(data.status,'Aviso', data.message);
           }

        } catch (error) {
          
            Alert.display('error', 'Algo ha salido mal', 'Lo sentimos');
        }
    }


}


export {AdmissionProccess};


