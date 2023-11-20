const http = require('http');
const fs = require('fs');
const path = require('path');

const server = http.createServer((req, res) => {
    // Manejo de rutas
    const filePath = req.url === '/' ? '/index.html' : req.url;
    const fullPath = path.join(__dirname, '../public', filePath);

    // Leer el archivo correspondiente
    fs.readFile(fullPath, (err, content) => {
        if (err) {
            if (err.code === 'ENOENT') {
                // Página no encontrada
                res.writeHead(404, { 'Content-Type': 'text/html' });
                res.end('<h1>404 Not Found</h1>');
            } else {
                // Error del servidor
                res.writeHead(500, { 'Content-Type': 'text/html' });
                res.end('<h1>500 Internal Server Error</h1>');
            }
        } else {
            // Éxito, enviar el contenido del archivo
            res.writeHead(200, { 'Content-Type': 'text/html' });
            res.end(content);
        }
    });
});

const PORT = process.env.PORT || 3000;

server.listen(PORT, () => {
    console.log(`Servidor Node.js en ejecución en http://localhost:${PORT}`);
});

// ... tu código existente ...

const server = http.createServer((req, res) => {
    if (req.method === 'POST' && req.url === '/login') {
        let body = '';

        req.on('data', chunk => {
            body += chunk.toString();
        });

        req.on('end', () => {
            const data = JSON.parse(body);

            // Simular la verificación de credenciales en el servidor
            if (data.username === 'usuario' && data.password === 'contrasena') {
                res.writeHead(200, { 'Content-Type': 'application/json' });
                res.end(JSON.stringify({ success: true, message: 'Inicio de sesión exitoso' }));
            } else {
                res.writeHead(401, { 'Content-Type': 'application/json' });
                res.end(JSON.stringify({ success: false, message: 'Credenciales incorrectas' }));
            }
        });
    } else {
        // ... manejo de rutas existente ...
    }
});

// ... tu código existente ...

