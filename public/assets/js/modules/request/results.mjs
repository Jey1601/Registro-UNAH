import { regular_expressions } from "../behavior/configuration.mjs";


class Results {
    static modalInstance = null;

    static verify() {
        this.showModal('verifySelection');
    }

    static getSelection() {
        const selection = document.querySelector('input[name="option"]:checked');
        const submitBtn = document.getElementById('submitBtn');

        

        if (selection) {
            console.log("Valor seleccionado:", selection.value);
            submitBtn.remove();
            this.hideModal();
            alert("Información enviada con exito");
            
            setTimeout(function(){
                window.location.href='../../index.html';
            },2000);

            

        } else {
            console.log("No se ha seleccionado ninguna opción.");
        }
    }

    static showModal(id) {
        const modalElement = document.getElementById(id);
        this.modalInstance = new bootstrap.Modal(modalElement);
        this.modalInstance.show();
    }

    static hideModal() {
        if (this.modalInstance) {
            this.modalInstance.hide();
            this.modalInstance = null; 
        }
    }

    
}

export { Results };
