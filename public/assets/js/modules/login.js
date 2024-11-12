class Login {
  static showLoginAdmissions() {
    const loginForm = `
        <!-- Login Admissions -->
            <div class="modal fade " id="loginModalAdmissions" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header" style="border-bottom: none;">
                    <div style="width: 100%; display: flex; justify-content: center;">
                    <h1 class="modal-title fs-5" style="color: #4D5057; " >Inicio de sesión</h1>
                    </div>
                
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:  20px;">
                    <form id="loginAdmissions">
                    <div class="mb-3">
                    
                        <input type="text" class="form-control" id="admissionsUser"  placeholder="Usuario..." style="box-shadow: 3px 3px 3px rgba(0, 0, 0, 0.2);">
                        
                    </div>
                    <div class="mb-3">
                    
                        <input type="password" class="form-control" id="admissionsPassword" placeholder="Contraseña...." style="box-shadow: 3px 3px 3px rgba(0, 0, 0, 0.2);" >
                    </div>
                    
                    <div class="modal-footer" style="border-top: none; display: flex; justify-content: center;">
                        <button type="submit" class=" btn-blue" style="border-radius: 20px; box-shadow: 3px 3px 3px rgba(0, 0, 0, 0.2);">Iniciar Sesión</button>
                    </div>
                    
                    
                    </form>

                </div>
                
                </div>
            </div>
            </div>
      `;
    document.body.insertAdjacentHTML("beforeend", loginForm);

    this.showModal('loginModalAdmissions');
    
  }


  static showLoginEstudents() {
    const loginForm = `
        <!-- Login Students -->
            <div class="modal fade " id="loginModalEstudents" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header" style="border-bottom: none;">
                    <div style="width: 100%; display: flex; justify-content: center;">
                    <h1 class="modal-title fs-5" style="color: #4D5057; " >Inicio de sesión</h1>
                    </div>
                
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:  20px;">
                    <form id="loginAdmissions">
                    <div class="mb-3">
                    
                        <input type="text" class="form-control" id="studentAccountNumber"  placeholder="Número de cuenta..." style="box-shadow: 3px 3px 3px rgba(0, 0, 0, 0.2);">
                        
                    </div>
                    <div class="mb-3">
                    
                        <input type="password" class="form-control" id="studentPassword" placeholder="Contraseña...." style="box-shadow: 3px 3px 3px rgba(0, 0, 0, 0.2);" >
                    </div>
                    
                    <div class="modal-footer" style="border-top: none; display: flex; justify-content: center;">
                        <button type="submit" class=" btn-yellow" style="border-radius: 20px; box-shadow: 3px 3px 3px rgba(0, 0, 0, 0.2);">Iniciar Sesión</button>
                    </div>
                    
                    
                    </form>

                    <div id="recoveryPassword" style="width: 100%; display: flex; justify-content: center;">
                    <a href="" style="text-decoration: none; color: rgba(0, 0, 0, 0.2);">¿Olvidaste tu contraseña?</a>
                    </div>
                </div>
                
                </div>
            </div>
            </div>
      `;
    document.body.insertAdjacentHTML("beforeend", loginForm);

    this.showModal('loginModalEstudents');
    
  }



  static showModal(id){
    const myLogin= new bootstrap.Modal(document.getElementById(id));
    myLogin.show();
  }
}


export{Login};