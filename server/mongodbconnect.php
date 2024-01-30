<?php

require '../vendor/autoload.php';
// Datos de conexión a MongoDB
$uri = "mongodb://localhost:27017";
$dbName = "Web";

try {
    // Conectar a MongoDB
    $client = new MongoDB\Client($uri);

    // Seleccionar la base de datos
    $dataBase = $client->$dbName;

} catch (MongoDB\Exception\Exception $e) {
    echo "Error de conexión: " . $e->getMessage();
}

?>
