import { Alert } from "../behavior/support.mjs"; 


async function submitCSVFile(params) {
    
    const formInscriptionGrades=document.getElementById('formInscriptionGrades');

    formInscriptionGrades.addEventListener('submit', async function (e) {
        e.preventDefault();

        const file_input = document.getElementById('csvFile');
        const form_data = new FormData();

        if (file_input.files.length > 0) {
            form_data.append('csvFile', file_input.files[0]);
            
            try {
                const response = await fetch ('../../../api/post/admissionAdmin/uploadRatingsCSV.php', {
                    method: 'POST',
                    body: form_data
                });

                const result = await response.json();
                formInscriptionGrades.reset();
                Alert.display(result.message, "warning");
          
            } catch (error) {
                Alert.display("No se pudo cargar el archivo", "warning");
              
            }
        } else {
            alert('Por favor, seleccionar un archivo CSV.');
        }
    })
}


const btn_upload = document.getElementById('btnUpload');
btn_upload.addEventListener('click', submitCSVFile);
