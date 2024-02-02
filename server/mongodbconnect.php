<?php

require 'vendor/autoload.php';
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

try {
    $announcementsC = $dataBase->Announcements; // Announcements' container
    $productsC = $dataBase->Products;   // Products' container
    $offersC = $dataBase->Offers;  // Offers' container
    $requestsC = $dataBase->Requests;   // Requests' container
    $vehiclesC  = $dataBase->Vehicles;  // Vehicles' container
    
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Error selecting the collection: " . $e->getMessage();
}

?>
