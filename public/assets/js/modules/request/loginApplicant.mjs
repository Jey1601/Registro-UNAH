import { Alert } from "../behavior/support.mjs";

class Applicant {
    static async auth(inputID, inputRequest) {
        const numID = inputID.value;
        const numRequest = inputRequest.value;

        const credentials = {
            numID: numID,
            numRequest: numRequest
        };

        fetch('../../../../../public/api/post/applicant/authApplicant.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(credentials)
        }).then(response => response.json()).then(result => {
            console.log(result);

            if(result.success) {
                sessionStorage.setItem('token', result.token);
                sessionStorage.setItem('numID', numID);
                window.location.href = '../../../../views/admissions/results.html';
            } else {
                Alert.display(result.message, 'warning');
            }
        }).catch(e => {
            console.log("Error en la peticion: ",e);
        })
    }
}

export { Applicant };
