document.addEventListener('DOMContentLoaded', () => {
    const token = sessionStorage.getItem('token');

    if(!token) {
        console.log("No se encontro token en el sessionStorage.");
        window.location.href = '../../../../index.html';
        return;
    }

    fetch('../../api/post/tokenValidation.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            'token': token
        })
    }).then(response => response.json()).then(data => {
        if (data.success === false) {
            window.location.href = '../../../../index.html';
        }
    }).catch (error => {
        console.error("Error en la validacion del token: ", error);
        window.location.href = '../../../../index.html';
    });
});