<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa con Leaflet</title>
    
    <link rel="stylesheet" href="../lib/leaflet/leaflet.css" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <style>
        #map {
            width: 100%;
            height: 95vh;
        }
        .legend {
            background-color: white;
            padding: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
        }

        .legend h4 {
            margin: 0 0 5px;
        }

        .legend p {
            margin: 0;
        }

        .legend span {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 5px;
            vertical-align: middle;
        }
    </style>
    
</head>
<body>
    <div id="map"></div>
</div>
</body>
</html>

<script src="../lib/leaflet/leaflet.js"></script>
<link rel="stylesheet" href="../lib/leaflet/MarkerCluster.css" />
<link rel="stylesheet" href="../lib/leaflet/MarkerCluster.Default.css" />
<script src="../lib/leaflet/leaflet.markercluster.js"></script>
<!-- Incluye tu archivo JavaScript externo -->
<script src="creating_markers.php"></script>
<script src="../public/js/map.js"></script>
