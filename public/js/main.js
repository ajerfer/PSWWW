document.addEventListener('DOMContentLoaded', function() {
    alert('¡Hola desde JavaScript!');
});

document.addEventListener('DOMContentLoaded', function() {
    // ... tu código existente ...

    // Función para manejar el inicio de sesión
    function login() {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        // Enviar los datos al servidor Node.js
        fetch('/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ username, password }),
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('result').innerText = data.message;
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // ...
});

