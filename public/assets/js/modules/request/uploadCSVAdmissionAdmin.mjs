import { Alert } from "../behavior/support.mjs"; 
import { Login } from "./login.mjs";
 const path = '../../../../';
async function submitCSVFile() {
    
    const formInscriptionGrades=document.getElementById('formInscriptionGrades');

    formInscriptionGrades.addEventListener('submit', async function (e) {
        e.preventDefault();

        const file_input = document.getElementById('csvFile');
        const form_data = new FormData();

        if (file_input.files.length > 0) {
            form_data.append('csvFile', file_input.files[0]);
            
            try {
                const response = await fetch (path+'api/post/admissionAdmin/uploadRatingsCSV.php', {
                    method: 'POST',
                    body: form_data
                });

                const result = await response.json();
                formInscriptionGrades.reset();
                Alert.display("warning", 'Aviso',result.message,path );
            } catch (error) {
                console.log(error);
                Alert.display('warning','Lo sentimos',"No se pudo cargar el archivo", path);
            }
        } else {
            Alert.display('warning','Vacio','Por favor, seleccionar un archivo CSV.',path);
        }
    })
}

const btn_upload = document.getElementById('btnUpload');
btn_upload.addEventListener('click', submitCSVFile);

const logoutBtn = document.getElementById('logoutBtn');
logoutBtn.addEventListener('click', function(event){
    event.preventDefault();
    Login.logout('../../index.html')
});



