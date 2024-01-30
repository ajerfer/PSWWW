<?php
// Connect to MySQL
$mysqli = new mysqli("localhost", "root", "", "prueba",3307);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get marker data from the POST request
$lat = $_POST['lat'];
$lng = $_POST['lng'];
$descrip = $_POST['descrip'];
$cat = $_POST['cat'];

// Save the marker to the database
$sql = "INSERT INTO markers (lat, lng, descrip, category) VALUES ('$lat', '$lng', '$descrip', '$cat')";
$result = $mysqli->query($sql);

if ($result) {
    echo json_encode(array('status' => 'success', 'message' => 'Marker saved successfully.'));
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Error saving marker.'));
}

// Close the database connection
$mysqli->close();
?>
