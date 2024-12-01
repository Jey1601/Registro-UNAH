import { Alert, Modal } from "../behavior/support.mjs";
import { RegionalCenter } from "./RegionalCenter.mjs";

class AdmissionProccess{
    static path = '../../../';

    static async getCurrentProccess() {
    
        try {
            const response = await fetch(this.path+"api/get/admisionProcess/currentAdmissionProccess.php");
    
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
            console.log(error);
            Alert.display('error', 'Algo ha salido mal', 'Lo sentimos');
        }
    }


}


export {AdmissionProccess};


