const http = require('http');
const fs = require('fs');
const path = require('path');

const server = http.createServer((req, res) => {
    const filePath = req.url === '/' ? '/index.php' : req.url;
    const fullPath = path.join(__dirname, '../public', filePath);

    // Read the appropiate file
    fs.readFile(fullPath, (err, content) => {
        if (err) {
            if (err.code === 'ENOENT') {
                // Page not found
                res.writeHead(404, { 'Content-Type': 'text/html' });
                res.end('<h1>404 Not Found</h1>');
            } else {
                // Server error
                res.writeHead(500, { 'Content-Type': 'text/html' });
                res.end('<h1>500 Internal Server Error</h1>');
            }
        } else {
            // Success
            res.writeHead(200, { 'Content-Type': 'text/html' });
            res.end(content);
        }
    });
});

const PORT = process.env.PORT || 3000;

server.listen(PORT, () => {
    console.log(`Servidor Node.js en ejecución en http://localhost:${PORT}`);
});
