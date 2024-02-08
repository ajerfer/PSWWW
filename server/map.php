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
            height: 90vh;
        }

        .legend {
            background-color: white;
            padding: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px; /* Adjust the margin as needed */
        }

        .legend-item img {
            width: 20px; /* Adjust the width as needed */
            height: 20px; /* Adjust the height as needed */
            margin-right: 5px; /* Adjust the margin as needed */
        }

        .tasks {
            background-color: white;
            padding: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
        }

        .tasks h4 {
            margin: 0 0 5px;
        }

        .tasks p {
            margin: 0;
        }

        .tasks span {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 5px;
            vertical-align: middle;
        }
        .filter-container {
  position: fixed;
  bottom: 20px;
  left: 20px;
  background-color: white;
  padding: 10px;
  border-radius: 5px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: row; /* Align horizontally */
}

.checkbox-label {
  position: relative;
  display: block;
  padding-left: 30px;
  margin-right: 10px; /* Adjust spacing between checkboxes */
  cursor: pointer;
  user-select: none;
}

.filter-checkbox {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 20px;
  width: 20px;
  background-color: #eee;
  border-radius: 4px;
  border: 1px solid #ccc;
}

.checkbox-label:hover .checkmark {
  background-color: #ddd;
}

.filter-checkbox:checked + .checkmark {
  background-color: #4CAF50;
  border: 1px solid #45a049;
}

.checkmark:after {
  content: '';
  position: absolute;
  display: none;
}

.filter-checkbox:checked + .checkmark:after {
  display: block;
}

.checkmark:after {
  left: 7px;
  top: 4px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

        /* Styles for the overlay/modal */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            align-items: center;
            justify-content: center;
            z-index: 1000; /* Adjust the z-index to be on top of everything */
        }

        .modal {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            max-width: 800px; /* Adjust the width as needed */
            width: 100%;
            text-align: center;
            position: relative;
            z-index: 1001; /* Ensure the modal is on top of the overlay */
        }

        /* Style for the close button */
        .close {
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        /* Style for the iframe */
        .iframe-container {
            height: 400px; /* Adjust the height as needed */
            overflow: auto;
        }

        /* Style for the iframe itself */
        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
    
</head>
<body>

    <div id="map"></div>
    <div class="filter-container">
        <label class="checkbox-label">
            <input type="checkbox" class="filter-checkbox" checked="checked" value="taken_requests">
            <span class="checkmark"></span>
            Taken requests
        </label>
        <label class="checkbox-label">
            <input type="checkbox" class="filter-checkbox" checked="checked" value="untaken_requests">
            <span class="checkmark"></span>
            Untaken requests
        </label>
        <label class="checkbox-label">
            <input type="checkbox" class="filter-checkbox" checked="checked" value="offers">
            <span class="checkmark"></span>
            Offers 
        </label>
        <label class="checkbox-label">
            <input type="checkbox" class="filter-checkbox" checked="checked" value="cars_active">
            <span class="checkmark"></span>
            Cars with active tasks
        </label>
        <label class="checkbox-label">
            <input type="checkbox" class="filter-checkbox" checked="checked" value="cars_not_active">
            <span class="checkmark"></span>
            Cars without active tasks
        </label>
        <label class="checkbox-label">
            <input type="checkbox" class="filter-checkbox" checked="checked" value="lines">
            <span class="checkmark"></span>
            Lines
        </label>
</div>

<div class="overlay" id="dialogOverlay">
    <div class="modal">
        <div class="iframe-container">
            <iframe src="./rescuer/manage_vehicle.php" frameborder="0"></iframe>
        </div>
    </div>
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
