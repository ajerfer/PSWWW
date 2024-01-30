<?php
// Connect to MySQL
$mysqli = new mysqli("localhost", "root", "", "prueba",3307);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch markers from the database
$markers = array();
$result = $mysqli->query("SELECT * FROM markers");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $markers[] = array(
            'lat' => $row['lat'],
            'lng' => $row['lng'],
            'descrip' => $row['descrip'],
            'cat' => $row['category'],
        );
    }
}

// Close the database connection
$mysqli->close();

// Return markers as JSON
header('Content-Type: application/json');
echo json_encode($markers);
?>