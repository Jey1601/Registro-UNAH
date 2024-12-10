import { Alert } from "../behavior/support.mjs"; 
import { Login } from "./login.mjs";
 const path = '../../../../';
async function submitCSVFile() {
    
    const formUploadStudents=document.getElementById('formUploadStudents');

    formUploadStudents.addEventListener('submit', async function (e) {
        e.preventDefault();


        const submitButton = document.querySelector('button[type="submit"]');
        submitButton.disabled = true; // Deshabilitar botón
        submitButton.textContent = 'Procesando...'; // Cambiar texto del botón


        const file_input = document.getElementById('csvFile');
        const form_data = new FormData();

        if (file_input.files.length > 0) {
            form_data.append('csvFile', file_input.files[0]);
            
            try {
                const response = await fetch (path+'api/post/diipAdmin/uploadStudentsCSV.php', {
                    method: 'POST',
                    body: form_data 
                });

                const result = await response.json();
                formUploadStudents.reset();
                Alert.display("warning", 'Aviso',result.message, path );
            } catch (error) {
                console.log(error);
                Alert.display('warning','Lo sentimos',"No se pudo cargar el archivo", path);
            }finally{
                submitButton.disabled = false; // Habilitar botón
                submitButton.textContent = 'Cargar Estudiantes'; // Restaurar texto
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
    Login.logout()
});

