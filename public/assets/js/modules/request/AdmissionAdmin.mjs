async function submitCSVFile(params) {
    document.getElementById('formInscriptionGrades').addEventListener('submit', async function (e) {
        e.preventDefault();

        const file_input = document.getElementById('csvFile');
        const form_data = new FormData();

        if (file_input.files.length > 0) {
            form_data.append('csvFile', file_input.files[0]);

            try {
                const response = await fetch ('../../../../api/post/admissionAdmin/uploadRatingsCSV.php', {
                    method: 'POST',
                    body: form_data
                });

                const result = await response.json();
                console.log(result.message);
            } catch (error) {
                console.log('Error al enviar el archivo: ', error);
            }
        } else {
            alert('Por favor, seleccionar un archivo CSV.');
        }
    })
}