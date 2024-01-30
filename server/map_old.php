<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa con Leaflet</title>
    
    <link rel="stylesheet" href="../lib/leaflet/leaflet.css" />
    
    <style>
        #map {
            width: 100%;
            height: 100vh;
        }
        #panel {
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <label>
        <input type="checkbox" id="rescue" onchange="filterMarkers()"> Rescue cars
    </label>
    <label>
        <input type="checkbox" id="request" onchange="filterMarkers()"> Requests
    </label>
    <label>
        <input type="checkbox" id="offers" onchange="filterMarkers()"> Offers
    </label>

    <div id="map"></div>
    <div id="panel">
    <h3>Panel</h3>
    <ul id="marker-list"></ul>
</div>
</body>
</html>

<script src="../lib/leaflet/leaflet.js"></script>
<link rel="stylesheet" href="../lib/leaflet/MarkerCluster.css" />
<link rel="stylesheet" href="../lib/leaflet/MarkerCluster.Default.css" />
<script src="../lib/leaflet/leaflet.markercluster.js"></script>
<!-- Incluye tu archivo JavaScript externo -->
<script src="../public/js/map.js"></script>
