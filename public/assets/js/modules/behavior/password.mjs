import { regular_expressions } from "./configuration.mjs";
class Password {
  /**
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-11
   */
  static togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const button = passwordInput.nextElementSibling;
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      button.innerHTML =
        '<img src="../assets/img/icons/flashlight-active-icon.png" alt="Mostrar contraseña">'; // Cambia al icono de mostrar
    } else {
      passwordInput.type = "password";
      button.innerHTML =
        '<img src="../assets/img/icons/flashlight-unactive-icon.png" alt="Ocultar contraseña">'; // Cambia al icono de ocultar
    }
  }

  /**
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-11
   */
  static verifyPassword(
    idPassword,
    idVerification,
    idButton,
    textRight,
    textWrong
  ) {
    const inputPassword = document.getElementById(idPassword);
    const inputPasswordV = document.getElementById(idVerification);

    const password = inputPassword.value;
    const passwordVerification = inputPasswordV.value;
    const button = document.getElementById(idButton);

    if (
      password == passwordVerification &&
      password != "" &&
      passwordVerification != ""
    ) {
      button.disabled = false;
      button.innerText = textRight;
    } else {
      button.disabled = "true";
      button.innerText = textWrong;
    }

    if (regular_expressions.password.test(password)) {
      inputPassword.classList.add("right-input");
      inputPassword.classList.remove("wrong-input");
    } else {
      inputPassword.classList.add("wrong-input");
      inputPassword.classList.remove("right-input");
    }

    if (regular_expressions.password.test(passwordVerification)) {
      inputPasswordV.classList.add("right-input");
      inputPasswordV.classList.remove("wrong-input");
    } else {
      inputPasswordV.classList.add("wrong-input");
      inputPasswordV.classList.remove("right-input");
    }
  }
}

export { Password };
