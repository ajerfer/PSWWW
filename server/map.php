<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es un administrador
if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php"); // Redirigir a la página de inicio de sesión
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="wid  th=device-width, initial-scale=1.0">
    <title>Mapa con Leaflet</title>
    
    <link rel="stylesheet" href="../lib/leaflet/leaflet.css" />
    
    <style>
        #map {
            width: 100%;
            height: 100vh;
        }
    </style>
</head>
<body>
    <label>
        <input type="checkbox" id="category1" onchange="filterMarkers()"> Category 1
    </label>
    <label>
        <input type="checkbox" id="category2" onchange="filterMarkers()"> Category 2
    </label>

    <div id="map"></div>
</body>
</html>

<script src="../lib/leaflet/leaflet.js"></script>
<!-- Incluye tu archivo JavaScript externo -->
<script src="../public/js/map.js"></script>
