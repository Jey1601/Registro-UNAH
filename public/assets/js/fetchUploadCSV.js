document.getElementById('csvForm').addEventListener('submit', function (e) {
    e.preventDefault(); //Para que el formulario no recargue la pagina

    const fileInput = document.getElementById('csvFile');
    const file = fileInput.files[0];

    if (file) {
        const reader = new FileReader(); //Para leer el contenido del CSV
        reader.onload = async function (event) {
            const csvContent = event.target.result;

            try {
                const response = await fetch('backend.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ csvData: csvContent })
                });

                const result = await response.json();
                console.log(result.message); // Maneja la respuesta del backend aquí
            } catch (error) {
                console.error('Error al enviar el archivo:', error);
            }
        };

        reader.readAsText(file); // Leer el archivo como texto
    } else {
        alert('Por favor, selecciona un archivo CSV.');
    }
});
