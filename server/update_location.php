<?php

include_once "databaseconnect.php";

$con = connect();

// Assume you have a table named 'locations' with columns 'id', 'latitude', and 'longitude'
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$id = $_POST['id'];

$sql = "UPDATE users SET lat = '$latitude', lng = '$longitude' WHERE userId = '$id'";

if ($con->query($sql) === TRUE) {
    echo "Location updated successfully";
} else {
    echo "Error updating location: " . $conn->error;
}

$con->close();
?>
