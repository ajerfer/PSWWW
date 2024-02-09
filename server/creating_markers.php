<?php
session_start();
include_once "mongodbconnect.php";
include_once "databaseconnect.php";

$con = connect();

if (isset($_SESSION['userId']) && $_SESSION['role'] == 'rescuer') {
    $user = $_SESSION['userId'];
    $sql = "SELECT lat, lng FROM users WHERE userId = $user";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    $rescuers = [2,$row['lat'],$row['lng']];
    $sql = "SELECT name FROM rescuers WHERE userId = $user";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    $rescuers[] = $row['name'];
    $rescuers[] = $user;

    $cursor = $vehiclesC->findOne(['userId' => $user]);

    // Array of ID
    $load = [[],[]];

    foreach ($cursor['load'] as $item) {
                $load[0][] = $item['id'];
                $load[1][] = $item['quantity']; 
    }

    $rescuers = [array_merge($rescuers,$load)];

} else if (isset($_SESSION['userId']) && $_SESSION['role'] == 'admin') {
    $sql = "SELECT userId, lat, lng FROM users WHERE userId in (SELECT userId FROM rescuers)";
    $result = $con->query($sql);
    $rescuers = [];
    while ($row = $result->fetch_assoc()) {
        $user = $row['userId'];
        $temp = [2,$row['lat'],$row['lng']];
        $sql1 = "SELECT name FROM rescuers WHERE userId = $user";
        $result1 = $con->query($sql1);
        $row1 = $result1->fetch_assoc();
        $temp[] = $row1['name'];
        $temp[] = $user;
        $rescuers[] = $temp;
    }
} else {
    header("Location: index.php"); // Redirigir a la página de inicio de sesión
    exit();
}
// Retrieve marker data
$cursor = $offersC->find();

// Array of ID
$data = [];

foreach ($cursor as $document) {
    foreach ($document['offers'] as $item) {
            $data[] = $item;
    }
}

$markers = [];

foreach ($data as $item) {
    $userID = $document['userId'];
    $sql = "SELECT lat, lng FROM users WHERE userID = $userID";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    $lat = $row['lat'];
    $lng = $row['lng'];
    $sql = "SELECT name, phone FROM citizens WHERE userID = $userID";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    $desc = "Name: " . $row['name']."<br>";
    $desc .= "Phone: " . $row['phone'] . "<br>";
    $desc .= "Date created: " . ($item['dateCreated']->toDateTime())->format('Y-m-d H:i:s') . "<br>";
    $desc .= "Products: ";
    foreach($item['products'] as $p) {
        $desc .= $p." ";
    }
    $desc .= "<br>Quantities: ";
    $quantity = [];
    foreach($item['nProducts'] as $n) {
        $desc .= $n." ";
        $quantity[] = $n;
    }
    $desc .= "<br>";

    $productsid = [];
    foreach($item['productsId'] as $pid) {
        $productsid[] = $pid;
    }
    
    $rescuerId = null;
    $date = null;
    if ($item['state'] == "1") {
        $rescuerId = $item['rescuerId'];
        $date = ($item['dateAccepted']->toDateTime())->format('Y-m-d H:i:s');
    }

    $markers[] = [3,$document['userId'],$item['id'],$item['state'], $rescuerId,$lat, $lng, $desc, $productsid, $quantity,$date];
}

$cursor = $requestsC->find();
$data = [];

foreach ($cursor as $document) {
    foreach ($document['requests'] as $item) {
            $data[] = $item;
    }
}

foreach ($data as $item) {
    $userID = $document['userId'];
    $sql = "SELECT lat, lng FROM users WHERE userID = $userID";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    $lat = $row['lat'];
    $lng = $row['lng'];
    $sql = "SELECT name, phone FROM citizens WHERE userID = $userID";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    $desc = "Name: " . $row['name']."<br>";
    $desc .= "Phone: " . $row['phone'] . "<br>";
    $desc .= "Date created: " . ($item['dateCreated']->toDateTime())->format('Y-m-d H:i:s') . "<br>";
    $desc .= "Products: " . $item['product'];
    $desc .= "<br>People: " . $item['nPersons'] . "<br>";
    
    $rescuerId = null;
    $date = null;
    if ($item['state'] == "1") {
        $rescuerId = $item['rescuerId'];
        $date = ($item['dateAccepted']->toDateTime())->format('Y-m-d H:i:s');
    }

    $markers[] = [4,$document['userId'],$item['id'],$item['state'], $rescuerId, $lat, $lng, $desc, $item['productId'], $item['nPersons'],$date];
}

$sql = "SELECT lat, lng FROM users WHERE userId = 1";
$result = $con->query($sql);
$row = $result->fetch_assoc();

// Return JSON response
header('Content-Type: application/json');
echo json_encode(array_merge([[0,$_SESSION['userId']],[1, $row['lat'], $row['lng'], 1]],$rescuers,$markers,[[5]]));
?>