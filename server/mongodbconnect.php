<?php

require 'vendor/autoload.php';
$uri = "mongodb://localhost:27017";
$dbName = "Web";

try {
    $client = new MongoDB\Client($uri);

    $dataBase = $client->$dbName;

} catch (MongoDB\Exception\Exception $e) {
    echo "Connection error: " . $e->getMessage();
}

try {
    $announcementsC = $dataBase->Announcements;
    $productsC = $dataBase->Products;
    $offersC = $dataBase->Offers;
    $requestsC = $dataBase->Requests;
    $vehiclesC  = $dataBase->Vehicles;
    
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Error selecting the collection: " . $e->getMessage();
}

?>
