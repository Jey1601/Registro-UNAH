
import { Login } from "../modules/request/login.mjs";


   /**
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-11
   */

   
const loginApplicant = document.getElementById('loginApplicant');
loginApplicant.addEventListener('submit',function(event){
    event.preventDefault();
    Login.authApplicant();
}); 