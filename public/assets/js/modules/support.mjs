class Alert{

    static alertPlaceholder = document.getElementById('alertPlaceholder')

    static display(message, type) {
        const wrapper = document.createElement('div')

        wrapper.innerHTML = [
            `<div class="alert alert-${type} alert-dismissible" role="alert">`,
            `   <div>${message}</div>`,
            '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
            '</div>'
          ].join('')


          alertPlaceholder.append(wrapper)
    }


}


class Modal{

    static modalInstance = null;

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


export{Alert, Modal};