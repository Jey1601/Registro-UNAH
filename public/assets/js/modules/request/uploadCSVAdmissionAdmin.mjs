import { Alert } from "../behavior/support.mjs"; 
import { Login } from "./login.mjs";

async function submitCSVFile() {
    
    const formInscriptionGrades=document.getElementById('formInscriptionGrades');

    formInscriptionGrades.addEventListener('submit', async function (e) {
        e.preventDefault();

        const file_input = document.getElementById('csvFile');
        const form_data = new FormData();

        if (file_input.files.length > 0) {
            form_data.append('csvFile', file_input.files[0]);
            
            try {
                const response = await fetch ('../../../public/api/post/admissionAdmin/uploadRatingsCSV.php', {
                    method: 'POST',
                    body: form_data
                });

                const result = await response.json();
                formInscriptionGrades.reset();
                Alert.display("warning", 'Aviso',result.message,'../../' );
            } catch (error) {
                console.log(error);
                Alert.display('warning','Lo sentimos',"No se pudo cargar el archivo", "../../");
            }
        } else {
            Alert.display('warning','Vacio','Por favor, seleccionar un archivo CSV.','../../');
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

const distributionBtn = document.getElementById('distributionBtn');

distributionBtn.addEventListener('click', fetchDistributionData);

async function fetchDistributionData() {
    try {
        // Realiza la solicitud al endpoint
        const response = await fetch('../../../public/api/get/admisionProcess/DistributionApplicantsByUserAdministrator.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        // Verifica si la respuesta es exitosa
        if (!response.ok) {
            throw new Error(`Error en la solicitud: ${response.status}`);
        }

        // Procesa la respuesta como JSON
        const data = await response.json();
        Alert.display(data.status, 'Aviso',data.message,'../../')
        // Maneja la respuesta
        console.log("Datos obtenidos:", data);
        // Aquí puedes procesar los datos según sea necesario
    } catch (error) {
        console.error("Error al obtener los datos:", error.message);
        Alert.display('error', 'Aviso',error.message,'../../')
    }
}