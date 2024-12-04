class Password{

    static togglePassword(fieldId) {
        const passwordInput = document.getElementById(fieldId);
        const button = passwordInput.nextElementSibling;
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            button.innerHTML = '<img src="../assets/img/icons/flashlight-active-icon.png" alt="Mostrar contraseña">'; // Cambia al icono de mostrar
        } else {
            passwordInput.type = 'password';
            button.innerHTML = '<img src="../assets/img/icons/flashlight-unactive-icon.png" alt="Ocultar contraseña">'; // Cambia al icono de ocultar
        }
    }

      static verifyPassword(idPassword, idVerification, idButton, textRight, textWrong) {
        const password = document.getElementById(idPassword).value;
        const passwordVerification = document.getElementById(idVerification).value;
        const button = document.getElementById(idButton);
    
        if (password !== passwordVerification || password === "" || passwordVerification === "") {
          button.disabled = true;
          button.innerText = textWrong;
        } else {
          button.disabled = false;
          button.innerText = textRight;
        }
      }
}

export {Password};