
document.addEventListener('DOMContentLoaded', () => {
    const token = sessionStorage.getItem('token');
    const type_user = sessionStorage.getItem('typeUser');
    
    if(!token) {
        sessionStorage.setItem('message',"No se encontro token en el sessionStorage.");
        window.location.href = '../../../index.html';
        return;
    }

    fetch('../../../api/post/tokenValidation.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            'token': token,
            'typeUser': type_user
        })
    }).then(response => response.json()).then(data => {
        if (data.success === false) {
            window.location.href = '../../../index.html';
        }
    }).catch (error => {
        console.error("Error en la validacion del token: ", error);
        window.location.href = '../../../index.html';
    });
});