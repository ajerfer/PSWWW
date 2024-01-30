<?php
include_once "mongodbconnect.php";
include_once "databaseconnect.php";

$collection = $dataBase->Offers;

// Retrieve marker data
$cursor = $collection->find();

// Array of ID
$data = [];

foreach ($cursor as $document) {
    foreach ($document['offer'] as $item) {
            $data[] = $item;
    }
}

$markers = [];

foreach ($data as $item) {
    $con = connect();
    $userID = $item['idUser'];
    $sql = "SELECT lat, lng, name, phone FROM citizens WHERE userID = $userID";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    $lat = $row['lat'];
    $lng = $row['lng'];
    $desc = "<p>Name: " . $row['name']."</p>";
    $desc .= "<p>Phone: " . $row['phone'] . "</p>";
    $desc .= "<p>Products: ";
    foreach($item['products'] as $p) {
        $desc .= $p." ";
    }
    $desc .= "</p><p>Quantities: ";
    foreach($item['nProducts'] as $n) {
        $desc .= $n." ";
    }
    $desc .= "</p>";
    $markers[] = [$lat, $lng, $desc];
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($markers);
?>