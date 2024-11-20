import { Alert } from "../behavior/support.mjs";

class AdmissionAdmin {
    static async authentication(inputUser, inputPassword) {
        const username = inputUser.value;
        const password = inputPassword.value;

        const credentials = {
            userAdmissionAdmin: username,
            passwordAdmissionAdmin: password
        }

        try {
            const response = await fetch('../../../../../public/api/post/admissionAdmin/authAdmissionAdmin.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(credentials)
            });

            const result = await response.json();
            console.log(result);

            if (result.success) {
                sessionStorage.setItem('token', result.token);
                window.location.href = '../../../../views/administration/admissions-admin.html';
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.log('Error al mandar la peticion: ',error);
        }
    }
}

export {AdmissionAdmin};

